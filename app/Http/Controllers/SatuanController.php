<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {
        $satuan = Satuan::withCount('barang')->paginate(15);
        return view('master.satuan.index', compact('satuan'));
    }

    public function create()
    {
        return view('master.satuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|unique:satuan',
        ]);

        Satuan::create($request->all());
        return redirect()->route('master.satuan.index')->with('success', 'Satuan berhasil ditambahkan.');
    }

    public function edit(Satuan $satuan)
    {
        return view('master.satuan.edit', compact('satuan'));
    }

    public function update(Request $request, Satuan $satuan)
    {
        $request->validate([
            'nama_satuan' => 'required|unique:satuan,nama_satuan,' . $satuan->id,
        ]);

        $satuan->update($request->all());
        return redirect()->route('master.satuan.index')->with('success', 'Satuan berhasil diperbarui.');
    }

    public function destroy(Satuan $satuan)
    {
        if ($satuan->barang()->exists()) {
            return back()->with('error', 'Satuan masih digunakan, tidak bisa dihapus.');
        }
        $satuan->delete();
        return redirect()->route('master.satuan.index')->with('success', 'Satuan berhasil dihapus.');
    }
}