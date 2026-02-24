<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    // ==================== MASUK ====================
    public function masukIndex(Request $request)
    {
        $query = Pembelian::with('supplier');
        if ($request->dari) $query->whereDate('tanggal_pembelian', '>=', $request->dari);
        if ($request->sampai) $query->whereDate('tanggal_pembelian', '<=', $request->sampai);
        if ($request->search) {
            $query->where('no_pembelian', 'like', '%' . $request->search . '%')
                ->orWhereHas('supplier', fn($q) => $q->where('nama_supplier', 'like', '%' . $request->search . '%'));
        }
        $pembelian = $query->orderByDesc('tanggal_pembelian')->paginate(15)->withQueryString();
        return view('pembelian.masuk.index', compact('pembelian'));
    }

    public function masukCreate()
    {
        $supplier = Supplier::orderBy('nama_supplier')->get();
        $barang   = Barang::with('satuan')->orderBy('nama_barang')->get();
        $noPembelian = 'PB-' . date('Ymd') . '-' . str_pad(
            (Pembelian::whereDate('created_at', today())->count() + 1), 3, '0', STR_PAD_LEFT
        );
        return view('pembelian.masuk.create', compact('supplier', 'barang', 'noPembelian'));
    }

    public function masukStore(Request $request)
    {
        $request->validate([
            'supplier_id'           => 'required|exists:supplier,id',
            'tanggal_pembelian'     => 'required|date',
            'items'                 => 'required|array|min:1',
            'items.*.barang_id'     => 'required|exists:barang,id',
            'items.*.qty'           => 'required|integer|min:1',
            'items.*.harga_beli'    => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $noPembelian = 'PB-' . date('Ymd') . '-' . str_pad(
                (Pembelian::whereDate('created_at', today())->count() + 1), 3, '0', STR_PAD_LEFT
            );

            $totalHarga = 0;
            foreach ($request->items as $item) {
                $totalHarga += $item['qty'] * $item['harga_beli'];
            }

            $pembelian = Pembelian::create([
                'no_pembelian'      => $noPembelian,
                'supplier_id'       => $request->supplier_id,
                'tanggal_pembelian' => $request->tanggal_pembelian,
                'total_harga'       => $totalHarga,
                'keterangan'        => $request->keterangan,
            ]);

            foreach ($request->items as $item) {
                // Simpan batch FIFO
                PembelianDetail::create([
                    'pembelian_id'  => $pembelian->id,
                    'barang_id'     => $item['barang_id'],
                    'qty_masuk'     => $item['qty'],
                    'sisa_qty'      => $item['qty'], // awal sisa = qty masuk
                    'harga_beli'    => $item['harga_beli'],
                    'subtotal'      => $item['qty'] * $item['harga_beli'],
                    'tanggal_masuk' => $request->tanggal_pembelian,
                ]);

                // Update stok barang
                Barang::where('id', $item['barang_id'])->increment('stok_total', $item['qty']);
            }
        });

        return redirect()->route('pembelian.masuk.index')->with('success', 'Pembelian berhasil disimpan.');
    }

    public function masukShow(Pembelian $pembelian)
    {
        $pembelian->load('supplier', 'detail.barang.satuan');
        return view('pembelian.masuk.show', compact('pembelian'));
    }

    public function masukDestroy(Pembelian $pembelian)
    {
        // Cek apakah ada batch yang sudah digunakan penjualan
        $adaDipakai = $pembelian->detail()->where('sisa_qty', '<', DB::raw('qty_masuk'))->exists();
        if ($adaDipakai) {
            return back()->with('error', 'Pembelian ini sudah ada barang yang terjual, tidak bisa dihapus.');
        }

        DB::transaction(function () use ($pembelian) {
            foreach ($pembelian->detail as $detail) {
                Barang::where('id', $detail->barang_id)->decrement('stok_total', $detail->qty_masuk);
            }
            $pembelian->delete();
        });

        return redirect()->route('pembelian.masuk.index')->with('success', 'Pembelian berhasil dihapus.');
    }

    // ==================== MUTASI ====================
    public function mutasiIndex(Request $request)
    {
        $mutasi = \App\Models\Mutasi::with(['barang', 'gudangAsal', 'gudangTujuan'])
            ->orderByDesc('tanggal_mutasi')->paginate(15);
        return view('pembelian.mutasi.index', compact('mutasi'));
    }

    public function mutasiCreate()
    {
        $barang = Barang::with(['satuan', 'gudang'])->where('stok_total', '>', 0)->orderBy('nama_barang')->get();
        $gudang = \App\Models\Gudang::orderBy('nama_gudang')->get();
        $noMutasi = 'MT-' . date('Ymd') . '-' . str_pad(
            (\App\Models\Mutasi::whereDate('created_at', today())->count() + 1), 3, '0', STR_PAD_LEFT
        );
        return view('pembelian.mutasi.create', compact('barang', 'gudang', 'noMutasi'));
    }

    public function mutasiStore(Request $request)
    {
        $request->validate([
            'barang_id'         => 'required|exists:barang,id',
            'gudang_asal_id'    => 'required|exists:gudang,id',
            'gudang_tujuan_id'  => 'required|exists:gudang,id|different:gudang_asal_id',
            'qty'               => 'required|integer|min:1',
            'tanggal_mutasi'    => 'required|date',
        ]);

        $barang = Barang::findOrFail($request->barang_id);
        if ($barang->stok_total < $request->qty) {
            return back()->with('error', 'Stok tidak mencukupi untuk mutasi.')->withInput();
        }

        DB::transaction(function () use ($request, $barang) {
            $noMutasi = 'MT-' . date('Ymd') . '-' . str_pad(
                (\App\Models\Mutasi::whereDate('created_at', today())->count() + 1), 3, '0', STR_PAD_LEFT
            );

            \App\Models\Mutasi::create([
                'no_mutasi'         => $noMutasi,
                'barang_id'         => $request->barang_id,
                'gudang_asal_id'    => $request->gudang_asal_id,
                'gudang_tujuan_id'  => $request->gudang_tujuan_id,
                'qty'               => $request->qty,
                'tanggal_mutasi'    => $request->tanggal_mutasi,
                'keterangan'        => $request->keterangan,
            ]);
            // Stok total tidak berubah, hanya lokasi gudang barang yang bergerak
            // Jika multi-gudang tracking diperlukan, tambah tabel stok_gudang
        });

        return redirect()->route('pembelian.mutasi.index')->with('success', 'Mutasi berhasil disimpan.');
    }

    // ==================== HISTORY ====================
    public function historyIndex(Request $request)
    {
        $query = PembelianDetail::with(['pembelian.supplier', 'barang.satuan']);
        if ($request->dari) $query->whereDate('tanggal_masuk', '>=', $request->dari);
        if ($request->sampai) $query->whereDate('tanggal_masuk', '<=', $request->sampai);
        if ($request->barang_id) $query->where('barang_id', $request->barang_id);

        $history = $query->orderByDesc('tanggal_masuk')->paginate(20)->withQueryString();
        $barang  = Barang::orderBy('nama_barang')->get();
        return view('pembelian.history', compact('history', 'barang'));
    }
}