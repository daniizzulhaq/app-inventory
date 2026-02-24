<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    public function index(Request $request)
    {
        $gudang = Gudang::withCount('barang')->paginate(15);
        return view('master.gudang.index', compact('gudang'));
    }

    public function create()
    {
        return view('master.gudang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_gudang'       => 'required|unique:gudang',
            'nama_gudang'       => 'required',
        ]);

        Gudang::create($request->all());
        return redirect()->route('master.gudang.index')->with('success', 'Gudang berhasil ditambahkan.');
    }

    public function edit(Gudang $gudang)
    {
        return view('master.gudang.edit', compact('gudang'));
    }

    public function update(Request $request, Gudang $gudang)
    {
        $request->validate([
            'kode_gudang' => 'required|unique:gudang,kode_gudang,' . $gudang->id,
            'nama_gudang' => 'required',
        ]);

        $gudang->update($request->all());
        return redirect()->route('master.gudang.index')->with('success', 'Gudang berhasil diperbarui.');
    }

    public function destroy(Gudang $gudang)
    {
        if ($gudang->barang()->exists()) {
            return back()->with('error', 'Gudang masih memiliki barang, tidak bisa dihapus.');
        }
        $gudang->delete();
        return redirect()->route('master.gudang.index')->with('success', 'Gudang berhasil dihapus.');
    }
}