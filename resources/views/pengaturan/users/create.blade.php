@extends('layouts.app')

@section('title', 'Tambah User')

@section('breadcrumb')
    <li class="breadcrumb-item text-muted">Sistem</li>
    <li class="breadcrumb-item"><a href="{{ route('pengaturan.users.index') }}" class="text-decoration-none">Kelola User</a></li>
    <li class="breadcrumb-item active">Tambah User</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person-plus me-2"></i>Tambah User Baru
            </div>
            <div class="card-body">
                <form action="{{ route('pengaturan.users.store') }}" method="POST">
                    @csrf

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="Nama lengkap..."
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="email@contoh.com"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            Role <span class="text-danger">*</span>
                        </label>
                        <select name="role"
                                class="form-select @error('role') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin"    {{ old('role') === 'admin'    ? 'selected' : '' }}>
                                👑 Admin — Akses penuh ke semua fitur
                            </option>
                            <option value="karyawan" {{ old('role') === 'karyawan' ? 'selected' : '' }}>
                                👤 Karyawan — Hanya Penjualan & Laporan
                            </option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1 text-primary"></i>
                            <strong>Admin:</strong> akses semua menu termasuk master data & pembelian.
                            <strong>Karyawan:</strong> hanya bisa input penjualan & lihat laporan.
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            Password <span class="text-danger">*</span>
                        </label>
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Minimal 8 karakter"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold small">
                            Konfirmasi Password <span class="text-danger">*</span>
                        </label>
                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               placeholder="Ulangi password"
                               required>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-check me-1"></i>Buat Akun
                        </button>
                        <a href="{{ route('pengaturan.users.index') }}"
                           class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection