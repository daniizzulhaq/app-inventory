@extends('layouts.app')
@section('title', 'Edit Satuan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('master.satuan.index') }}" class="text-decoration-none">Satuan</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil me-2"></i>Edit Satuan: {{ $satuan->nama_satuan }}</div>
            <div class="card-body">
                <form action="{{ route('master.satuan.update', $satuan) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Nama Satuan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_satuan"
                                   class="form-control @error('nama_satuan') is-invalid @enderror"
                                   value="{{ old('nama_satuan', $satuan->nama_satuan) }}" required>
                            @error('nama_satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Singkatan</label>
                            <input type="text" name="singkatan" class="form-control"
                                   value="{{ old('singkatan', $satuan->singkatan) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $satuan->keterangan) }}</textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                        <a href="{{ route('master.satuan.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection