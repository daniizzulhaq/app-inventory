<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangBatch;
use App\Models\PembelianDetail;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenjualanController extends Controller
{
    public function keluarIndex(Request $request)
    {
        $query = Penjualan::query();
        if ($request->dari)   $query->whereDate('tanggal_penjualan', '>=', $request->dari);
        if ($request->sampai) $query->whereDate('tanggal_penjualan', '<=', $request->sampai);
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('no_invoice', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_pembeli', 'like', '%' . $request->search . '%');
            });
        }
        $penjualan = $query->orderByDesc('tanggal_penjualan')->paginate(15)->withQueryString();
        return view('penjualan.keluar.index', compact('penjualan'));
    }

    public function keluarCreate()
    {
        $barang    = Barang::with('satuan')->where('stok_total', '>', 0)->orderBy('nama_barang')->get();
        $noInvoice = 'INV-' . date('Ymd') . '-' . str_pad(
            (Penjualan::whereDate('created_at', today())->count() + 1), 3, '0', STR_PAD_LEFT
        );

        $batchPerBarang = [];
        foreach ($barang as $b) {
            $batchPerBarang[$b->id] = $this->getBatchListForBarang($b->id);
        }

        return view('penjualan.keluar.create', compact('barang', 'noInvoice', 'batchPerBarang'));
    }

    protected function tglStr($val): string
    {
        if ($val instanceof \Carbon\Carbon) return $val->format('Y-m-d');
        return substr((string) $val, 0, 10);
    }

    protected function getBatchListForBarang(int $barangId): array
    {
        $pembelianDetails = PembelianDetail::with('pembelian')
            ->where('barang_id', $barangId)
            ->where('sisa_qty', '>', 0)
            ->orderBy('tanggal_masuk', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $batchGrouped   = [];
        $usedPerTanggal = [];

        BarangBatch::where('barang_id', $barangId)
            ->orderBy('tanggal_masuk', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->each(function ($bb) use (&$batchGrouped) {
                $key = $this->tglStr($bb->tanggal_masuk);
                $batchGrouped[$key][] = $bb;
            });

        $result = [];
        foreach ($pembelianDetails as $pd) {
            $tgl   = $this->tglStr($pd->tanggal_masuk);
            $idx   = $usedPerTanggal[$tgl] ?? 0;
            $batch = $batchGrouped[$tgl][$idx] ?? null;
            $usedPerTanggal[$tgl] = $idx + 1;

            $expiredDate = null;
            $status      = 'no_expired';
            if ($batch && $batch->expired_date) {
                $expiredDate = Carbon::parse($batch->expired_date)->format('d/m/Y');
                $status      = $batch->status_expired ?? 'aman';
            }

            $result[] = [
                'id'            => $pd->id,
                'no_batch'      => $batch->no_batch ?? ($pd->pembelian->no_pembelian ?? 'PD-' . $pd->id),
                'sisa_qty'      => $pd->sisa_qty,
                'expired_date'  => $expiredDate,
                'status'        => $status,
                'tanggal_masuk' => Carbon::parse($pd->tanggal_masuk)->format('d/m/Y'),
            ];
        }

        return $result;
    }

    public function keluarStore(Request $request)
    {
        $request->validate([
            'tanggal_penjualan'  => 'required|date',
            'items'              => 'required|array|min:1',
            'items.*.barang_id'  => 'required|exists:barang,id',
            'items.*.qty'        => 'required|integer|min:1',
            'items.*.harga_jual' => 'required|numeric|min:0',
        ]);

        foreach ($request->items as $item) {
            $barang   = Barang::find($item['barang_id']);
            $sisaStok = $barang->stok_total - $item['qty'];

            if ($barang->stok_total < $item['qty']) {
                return back()->with('error', "Stok {$barang->nama_barang} tidak mencukupi. Stok: {$barang->stok_total}.")->withInput();
            }
            if ($sisaStok < ($barang->stok_minimum ?? 0)) {
                return back()->with('error', "Stok {$barang->nama_barang} akan di bawah minimum ({$barang->stok_minimum}).")->withInput();
            }
            if (!empty($item['batches'])) {
                $totalBatchQty = collect($item['batches'])->sum('qty');
                if ($totalBatchQty != $item['qty']) {
                    return back()->with('error', "Total qty batch untuk {$barang->nama_barang} ({$totalBatchQty}) tidak sama dengan qty item ({$item['qty']}).")->withInput();
                }
            }
        }

        DB::transaction(function () use ($request) {
            $noInvoice = 'INV-' . date('Ymd') . '-' . str_pad(
                (Penjualan::whereDate('created_at', today())->count() + 1), 3, '0', STR_PAD_LEFT
            );

            $totalHarga = 0;
            $totalHpp   = 0;
            $detailData = [];

            foreach ($request->items as $item) {
                $qty        = $item['qty'];
                $hargaJual  = $item['harga_jual'];
                $subtotal   = $qty * $hargaJual;
                $totalHarga += $subtotal;
                $hpp        = 0;
                $expiredInfo = []; // <-- kumpulkan info expired untuk disimpan

                if (!empty($item['batches'])) {
                    // ===== MODE MANUAL =====
                    foreach ($item['batches'] as $batchItem) {
                        $pd = PembelianDetail::find($batchItem['batch_id']);
                        if (!$pd) continue;

                        $ambil = min($pd->sisa_qty, (int) $batchItem['qty']);
                        $hpp  += $ambil * $pd->harga_beli;
                        $pd->decrement('sisa_qty', $ambil);

                        // Cari expired_date dari barang_batch
                        $expiredDate = null;
                        $status      = 'no_expired';
                        $bb = BarangBatch::where('barang_id', $pd->barang_id)
                            ->whereDate('tanggal_masuk', $this->tglStr($pd->tanggal_masuk))
                            ->orderBy('id', 'asc')
                            ->first();
                        if ($bb && $bb->expired_date) {
                            $expiredDate = Carbon::parse($bb->expired_date)->format('d/m/Y');
                            $status      = $bb->status_expired ?? 'aman';
                        }

                        $expiredInfo[] = [
                            'expired_date' => $expiredDate,
                            'qty'          => $ambil,
                            'status'       => $status,
                        ];
                    }
                } else {
                    // ===== MODE FIFO OTOMATIS =====
                    $sisaQtyDiambil = $qty;
                    $pdBatches = PembelianDetail::where('barang_id', $item['barang_id'])
                        ->where('sisa_qty', '>', 0)
                        ->orderBy('tanggal_masuk', 'asc')
                        ->orderBy('id', 'asc')
                        ->get();

                    foreach ($pdBatches as $pd) {
                        if ($sisaQtyDiambil <= 0) break;
                        $ambil = min($pd->sisa_qty, $sisaQtyDiambil);
                        $hpp  += $ambil * $pd->harga_beli;
                        $sisaQtyDiambil -= $ambil;
                        $pd->decrement('sisa_qty', $ambil);

                        // Cari expired_date dari barang_batch
                        $expiredDate = null;
                        $status      = 'no_expired';
                        $bb = BarangBatch::where('barang_id', $pd->barang_id)
                            ->whereDate('tanggal_masuk', $this->tglStr($pd->tanggal_masuk))
                            ->orderBy('id', 'asc')
                            ->first();
                        if ($bb && $bb->expired_date) {
                            $expiredDate = Carbon::parse($bb->expired_date)->format('d/m/Y');
                            $status      = $bb->status_expired ?? 'aman';
                        }

                        $expiredInfo[] = [
                            'expired_date' => $expiredDate,
                            'qty'          => $ambil,
                            'status'       => $status,
                        ];
                    }
                }

                $totalHpp += $hpp;

                $detailData[] = [
                    'barang_id'    => $item['barang_id'],
                    'qty'          => $qty,
                    'harga_jual'   => $hargaJual,
                    'hpp'          => $hpp,
                    'subtotal'     => $subtotal,
                    'laba'         => $subtotal - $hpp,
                    'expired_info' => json_encode($expiredInfo), // <-- simpan ke DB
                ];

                Barang::where('id', $item['barang_id'])->decrement('stok_total', $qty);
            }

            $penjualan = Penjualan::create([
                'no_invoice'        => $noInvoice,
                'nama_pembeli'      => $request->nama_pembeli,
                'tanggal_penjualan' => $request->tanggal_penjualan,
                'total_harga'       => $totalHarga,
                'total_hpp'         => $totalHpp,
                'laba'              => $totalHarga - $totalHpp,
                'keterangan'        => $request->keterangan,
            ]);

            foreach ($detailData as $d) {
                $d['penjualan_id'] = $penjualan->id;
                PenjualanDetail::create($d);
            }
        });

        return redirect()->route('penjualan.keluar.index')->with('success', 'Penjualan berhasil disimpan.');
    }

    public function keluarShow(Penjualan $penjualan)
    {
        $penjualan->load('detail.barang.satuan');

        // Baca langsung dari kolom expired_info yang sudah disimpan
        $expiredPerDetail = [];
        foreach ($penjualan->detail as $detail) {
            $info = $detail->expired_info;
            if (is_string($info)) {
                $info = json_decode($info, true) ?? [];
            }
            $expiredPerDetail[$detail->id] = $info ?? [];
        }

        return view('penjualan.keluar.show', compact('penjualan', 'expiredPerDetail'));
    }

    public function keluarDestroy(Penjualan $penjualan)
    {
        DB::transaction(function () use ($penjualan) {
            $penjualan->load('detail');

            foreach ($penjualan->detail as $detail) {
                Barang::where('id', $detail->barang_id)->increment('stok_total', $detail->qty);

                $sisaKembali = $detail->qty;
                $batches = PembelianDetail::where('barang_id', $detail->barang_id)
                    ->whereColumn('sisa_qty', '<', 'qty_masuk')
                    ->orderBy('tanggal_masuk', 'desc')
                    ->orderBy('id', 'desc')
                    ->get();

                foreach ($batches as $batch) {
                    if ($sisaKembali <= 0) break;
                    $kapasitas = $batch->qty_masuk - $batch->sisa_qty;
                    $kembali   = min($kapasitas, $sisaKembali);
                    $batch->increment('sisa_qty', $kembali);
                    $sisaKembali -= $kembali;
                }
            }

            $penjualan->delete();
        });

        return redirect()->route('penjualan.keluar.index')->with('success', 'Penjualan berhasil dihapus.');
    }

    public function historyIndex(Request $request)
    {
        $query = Penjualan::query();
        if ($request->dari)   $query->whereDate('tanggal_penjualan', '>=', $request->dari);
        if ($request->sampai) $query->whereDate('tanggal_penjualan', '<=', $request->sampai);
        $penjualan = $query->orderByDesc('tanggal_penjualan')->paginate(20)->withQueryString();
        return view('penjualan.history', compact('penjualan'));
    }
}