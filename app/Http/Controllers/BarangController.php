<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangBatch;
use App\Models\Satuan;
use App\Models\Gudang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with(['satuan', 'gudang', 'batches']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $request->search . '%');
            });
        }

        $barang = $query->orderBy('nama_barang')->paginate(15)->withQueryString();
        return view('master.barang.index', compact('barang'));
    }

    public function show(Barang $barang)
    {
        $barang->load('satuan', 'gudang');

        // Ambil semua batch lalu kelompokkan berdasarkan status expired
        $semuaBatch = BarangBatch::where('barang_id', $barang->id)
                        ->orderBy('expired_date')
                        ->get();

        $grouped = [
            'expired'    => $semuaBatch->filter(fn($b) => $b->status_expired === 'expired'),
            'warning'    => $semuaBatch->filter(fn($b) => $b->status_expired === 'warning'),
            'aman'       => $semuaBatch->filter(fn($b) => $b->status_expired === 'aman'),
            'no_expired' => $semuaBatch->filter(fn($b) => $b->status_expired === 'no_expired'),
        ];

        return view('master.barang.show', compact('barang', 'grouped', 'semuaBatch'));
    }

    public function create()
    {
        $satuan = Satuan::orderBy('nama_satuan')->get();
        $gudang = Gudang::orderBy('nama_gudang')->get();
        return view('master.barang.create', compact('satuan', 'gudang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang'  => 'required|unique:barang',
            'nama_barang'  => 'required',
            'satuan_id'    => 'required|exists:satuan,id',
            'gudang_id'    => 'required|exists:gudang,id',
            'harga_jual'   => 'required|numeric|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
        ]);

        Barang::create($request->all());
        return redirect()->route('master.barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Barang $barang)
    {
        $satuan = Satuan::orderBy('nama_satuan')->get();
        $gudang = Gudang::orderBy('nama_gudang')->get();
        return view('master.barang.edit', compact('barang', 'satuan', 'gudang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode_barang'  => 'required|unique:barang,kode_barang,' . $barang->id,
            'nama_barang'  => 'required',
            'satuan_id'    => 'required|exists:satuan,id',
            'gudang_id'    => 'required|exists:gudang,id',
            'harga_jual'   => 'required|numeric|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
        ]);

        $hargaLama = $barang->harga_jual;
        $barang->update($request->all());

        // Sync harga_beli di batch jika harga jual berubah
        if ($hargaLama != $request->harga_jual) {
            BarangBatch::where('barang_id', $barang->id)
                ->update(['harga_beli' => $request->harga_jual]);
        }

        return redirect()->route('master.barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->stok_total > 0) {
            return back()->with('error', 'Barang masih memiliki stok, tidak bisa dihapus.');
        }
        $barang->delete();
        return redirect()->route('master.barang.index')->with('success', 'Barang berhasil dihapus.');
    }

    public function getStok(Barang $barang)
    {
        return response()->json([
            'stok_total' => $barang->stok_total,
            'harga_jual' => $barang->harga_jual,
            'satuan'     => $barang->satuan->nama_satuan ?? '',
        ]);
    }
}