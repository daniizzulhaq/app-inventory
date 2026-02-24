<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();
        if ($request->search) {
            $query->where('nama_supplier', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_supplier', 'like', '%' . $request->search . '%');
        }
        $supplier = $query->orderBy('nama_supplier')->paginate(15)->withQueryString();
        return view('master.supplier.index', compact('supplier'));
    }

    public function create()
    {
        return view('master.supplier.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_supplier'  => 'required|unique:supplier',
            'nama_supplier'  => 'required',
            'telepon'        => 'nullable',
            'email'          => 'nullable|email',
        ]);

        Supplier::create($request->all());
        return redirect()->route('master.supplier.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        return view('master.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'kode_supplier'  => 'required|unique:supplier,kode_supplier,' . $supplier->id,
            'nama_supplier'  => 'required',
            'email'          => 'nullable|email',
        ]);

        $supplier->update($request->all());
        return redirect()->route('master.supplier.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->pembelian()->exists()) {
            return back()->with('error', 'Supplier sudah memiliki transaksi, tidak bisa dihapus.');
        }
        $supplier->delete();
        return redirect()->route('master.supplier.index')->with('success', 'Supplier berhasil dihapus.');
    }
}