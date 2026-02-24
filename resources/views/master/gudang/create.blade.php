@extends('layouts.app')
@section('title', 'Tambah Gudang')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('master.gudang.index') }}" class="text-decoration-none">Gudang</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Tambah Gudang Baru</div>
            <div class="card-body">
                <form action="{{ route('master.gudang.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Gudang <span class="text-danger">*</span></label>
                            <input type="text" name="kode_gudang"
                                   class="form-control @error('kode_gudang') is-invalid @enderror"
                                   value="{{ old('kode_gudang') }}" placeholder="Cth: GDG-001" required>
                            @error('kode_gudang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Gudang <span class="text-danger">*</span></label>
                            <input type="text" name="nama_gudang"
                                   class="form-control @error('nama_gudang') is-invalid @enderror"
                                   value="{{ old('nama_gudang') }}" placeholder="Nama gudang..." required>
                            @error('nama_gudang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control"
                                   value="{{ old('lokasi') }}" placeholder="Lokasi gudang (opsional)">
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
                        <a href="{{ route('master.gudang.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection