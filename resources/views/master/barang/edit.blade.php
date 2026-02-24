@extends('layouts.app')
@section('title', 'Edit Barang')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('master.barang.index') }}" class="text-decoration-none">Barang</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil me-2"></i>Edit Barang: {{ $barang->nama_barang }}</div>
            <div class="card-body">
                <form action="{{ route('master.barang.update', $barang) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Barang <span class="text-danger">*</span></label>
                            <input type="text" name="kode_barang" class="form-control @error('kode_barang') is-invalid @enderror"
                                   value="{{ old('kode_barang', $barang->kode_barang) }}" required>
                            @error('kode_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror"
                                   value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                            @error('nama_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                            <select name="satuan_id" class="form-select" required>
                                @foreach($satuan as $s)
                                <option value="{{ $s->id }}" {{ $barang->satuan_id == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama_satuan }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Gudang <span class="text-danger">*</span></label>
                            <select name="gudang_id" class="form-select" required>
                                @foreach($gudang as $g)
                                <option value="{{ $g->id }}" {{ $barang->gudang_id == $g->id ? 'selected' : '' }}>{{ $g->nama_gudang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga Jual <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga_jual" class="form-control"
                                       value="{{ old('harga_jual', $barang->harga_jual) }}" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Stok Minimum</label>
                            <input type="number" name="stok_minimum" class="form-control"
                                   value="{{ old('stok_minimum', $barang->stok_minimum) }}" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Stok Saat Ini</label>
                            <input type="text" class="form-control" value="{{ $barang->stok_total }}" disabled>
                            <small class="text-muted">Stok diubah melalui transaksi pembelian/penjualan</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $barang->keterangan) }}</textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                        <a href="{{ route('master.barang.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
