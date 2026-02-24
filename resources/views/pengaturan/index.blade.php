@extends('layouts.app')
@section('title', 'Pengaturan')
@section('breadcrumb')
    <li class="breadcrumb-item active">Pengaturan</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-gear me-2"></i>Pengaturan Aplikasi</div>
            <div class="card-body">
                <form action="{{ route('pengaturan.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12 text-center">
                            @if($setting->logo)
                                <img src="{{ asset('storage/' . $setting->logo) }}"
                                     alt="Logo" class="img-thumbnail mb-2" style="max-height:100px">
                            @else
                                <div class="text-muted mb-2"><i class="bi bi-building fs-1"></i></div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Perusahaan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_perusahaan"
                                   class="form-control @error('nama_perusahaan') is-invalid @enderror"
                                   value="{{ old('nama_perusahaan', $setting->nama_perusahaan) }}" required>
                            @error('nama_perusahaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Logo Perusahaan</label>
                            <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror"
                                   accept="image/*">
                            @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Format: JPG, PNG. Maks: 2MB</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Telepon</label>
                            <input type="text" name="telepon" class="form-control"
                                   value="{{ old('telepon', $setting->telepon) }}" placeholder="Nomor telepon...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', $setting->email) }}" placeholder="Email perusahaan...">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3"
                                      placeholder="Alamat perusahaan...">{{ old('alamat', $setting->alamat) }}</textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection