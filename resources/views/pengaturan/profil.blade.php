@extends('layouts.app')
@section('title', 'Profil')
@section('breadcrumb')
    <li class="breadcrumb-item active">Profil</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">

        <!-- Edit Profil -->
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-person-circle me-2"></i>Edit Profil</div>
            <div class="card-body">
                <form action="{{ route('pengaturan.profil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12 text-center">
                            @if(Auth::user()->foto)
                                <img src="{{ asset('storage/' . Auth::user()->foto) }}"
                                     alt="Foto" class="rounded-circle mb-2" style="width:80px;height:80px;object-fit:cover">
                            @else
                                <div class="mb-2">
                                    <i class="bi bi-person-circle text-secondary" style="font-size:60px"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Foto Profil</label>
                            <input type="file" name="foto"
                                   class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                            @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Format: JPG, PNG. Maks: 2MB</small>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Profil
                    </button>
                </form>
            </div>
        </div>

        <!-- Ganti Password -->
        <div class="card">
            <div class="card-header"><i class="bi bi-shield-lock me-2"></i>Ganti Password</div>
            <div class="card-body">
                <form action="{{ route('pengaturan.ganti-password') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Password Lama <span class="text-danger">*</span></label>
                            <input type="password" name="password_lama"
                                   class="form-control @error('password_lama') is-invalid @enderror" required>
                            @error('password_lama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Password Baru <span class="text-danger">*</span></label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Minimal 8 karakter</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-shield-lock me-1"></i>Ganti Password
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
