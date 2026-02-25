<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\PembelianDetail;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    // ==================== KELUAR (FIFO) ====================
    public function keluarIndex(Request $request)
    {
        $query = Penjualan::query();
        if ($request->dari) $query->whereDate('tanggal_penjualan', '>=', $request->dari);
        if ($request->sampai) $query->whereDate('tanggal_penjualan', '<=', $request->sampai);
        if ($request->search) {
            $query->where('no_invoice', 'like', '%' . $request->search . '%')
                ->orWhere('nama_pembeli', 'like', '%' . $request->search . '%');
        }
        $penjualan = $query->orderByDesc('tanggal_penjualan')->paginate(15)->withQueryString();
        return view('penjualan.keluar.index', compact('penjualan'));
    }

    public function keluarCreate()
    {
        $barang     = Barang::with('satuan')->where('stok_total', '>', 0)->orderBy('nama_barang')->get();
        $noInvoice  = 'INV-' . date('Ymd') . '-' . str_pad(
            (Penjualan::whereDate('created_at', today())->count() + 1), 3, '0', STR_PAD_LEFT
        );
        return view('penjualan.keluar.create', compact('barang', 'noInvoice'));
    }

    public function keluarStore(Request $request)
    {
        $request->validate([
            'tanggal_penjualan' => 'required|date',
            'items'             => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barang,id',
            'items.*.qty'       => 'required|integer|min:1',
            'items.*.harga_jual' => 'required|numeric|min:0',
        ]);

        // Cek stok semua item sebelum proses
        foreach ($request->items as $item) {
            $barang      = Barang::find($item['barang_id']);
            $stokMinimum = $barang->stok_minimum ?? 10;
            $sisaStok    = $barang->stok_total - $item['qty'];

            if ($barang->stok_total < $item['qty']) {
                return back()->with('error', "Stok {$barang->nama_barang} tidak mencukupi. Stok saat ini: {$barang->stok_total}.")->withInput();
            }

            if ($sisaStok < $stokMinimum) {
                return back()->with('error', "Penjualan {$barang->nama_barang} tidak dapat diproses. Sisa stok setelah penjualan ({$sisaStok}) akan berada di bawah batas minimum ({$stokMinimum}).")->withInput();
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
                $qty       = $item['qty'];
                $hargaJual = $item['harga_jual'];
                $subtotal  = $qty * $hargaJual;
                $totalHarga += $subtotal;

                // ===== FIFO ALGORITHM =====
                $hpp = 0;
                $sisaQtyDiambil = $qty;

                $batches = PembelianDetail::where('barang_id', $item['barang_id'])
                    ->where('sisa_qty', '>', 0)
                    ->orderBy('tanggal_masuk', 'asc')
                    ->orderBy('id', 'asc')
                    ->get();

                foreach ($batches as $batch) {
                    if ($sisaQtyDiambil <= 0) break;

                    $ambil = min($batch->sisa_qty, $sisaQtyDiambil);
                    $hpp  += $ambil * $batch->harga_beli;
                    $sisaQtyDiambil -= $ambil;

                    // Kurangi sisa_qty batch
                    $batch->decrement('sisa_qty', $ambil);
                }

                $totalHpp += $hpp;
                $laba      = $subtotal - $hpp;

                $detailData[] = [
                    'barang_id'  => $item['barang_id'],
                    'qty'        => $qty,
                    'harga_jual' => $hargaJual,
                    'hpp'        => $hpp,
                    'subtotal'   => $subtotal,
                    'laba'       => $laba,
                ];

                // Kurangi stok total barang
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
        return view('penjualan.keluar.show', compact('penjualan'));
    }

    // ==================== HISTORY ====================
    public function historyIndex(Request $request)
    {
        $query = Penjualan::query();
        if ($request->dari) $query->whereDate('tanggal_penjualan', '>=', $request->dari);
        if ($request->sampai) $query->whereDate('tanggal_penjualan', '<=', $request->sampai);
        $penjualan = $query->orderByDesc('tanggal_penjualan')->paginate(20)->withQueryString();
        return view('penjualan.history', compact('penjualan'));
    }
}