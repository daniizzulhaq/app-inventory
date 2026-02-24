@extends('layouts.app')
@section('title', 'Tambah Satuan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('master.satuan.index') }}" class="text-decoration-none">Satuan</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Tambah Satuan Baru</div>
            <div class="card-body">
                <form action="{{ route('master.satuan.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Nama Satuan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_satuan"
                                   class="form-control @error('nama_satuan') is-invalid @enderror"
                                   value="{{ old('nama_satuan') }}" placeholder="Cth: Kilogram" required>
                            @error('nama_satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Singkatan</label>
                            <input type="text" name="singkatan" class="form-control"
                                   value="{{ old('singkatan') }}" placeholder="Cth: Kg">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2"
                                      placeholder="Keterangan (opsional)">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                        <a href="{{ route('master.satuan.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection