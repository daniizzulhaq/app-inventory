@extends('layouts.app')
@section('title', 'Edit Gudang')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('master.gudang.index') }}" class="text-decoration-none">Gudang</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil me-2"></i>Edit Gudang: {{ $gudang->nama_gudang }}</div>
            <div class="card-body">
                <form action="{{ route('master.gudang.update', $gudang) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Gudang <span class="text-danger">*</span></label>
                            <input type="text" name="kode_gudang"
                                   class="form-control @error('kode_gudang') is-invalid @enderror"
                                   value="{{ old('kode_gudang', $gudang->kode_gudang) }}" required>
                            @error('kode_gudang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Gudang <span class="text-danger">*</span></label>
                            <input type="text" name="nama_gudang"
                                   class="form-control @error('nama_gudang') is-invalid @enderror"
                                   value="{{ old('nama_gudang', $gudang->nama_gudang) }}" required>
                            @error('nama_gudang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control"
                                   value="{{ old('lokasi', $gudang->lokasi) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $gudang->keterangan) }}</textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                        <a href="{{ route('master.gudang.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection