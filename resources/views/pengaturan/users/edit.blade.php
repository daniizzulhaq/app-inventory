@extends('layouts.app')

@section('title', 'Edit User')

@section('breadcrumb')
    <li class="breadcrumb-item text-muted">Sistem</li>
    <li class="breadcrumb-item"><a href="{{ route('pengaturan.users.index') }}" class="text-decoration-none">Kelola User</a></li>
    <li class="breadcrumb-item active">Edit: {{ $user->name }}</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil-square me-2"></i>Edit User: <strong>{{ $user->name }}</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('pengaturan.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}"
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
                               value="{{ old('email', $user->email) }}"
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
                                {{ $user->id === Auth::id() ? 'disabled' : '' }}
                                required>
                            <option value="admin"    {{ old('role', $user->role) === 'admin'    ? 'selected' : '' }}>
                                👑 Admin — Akses penuh ke semua fitur
                            </option>
                            <option value="karyawan" {{ old('role', $user->role) === 'karyawan' ? 'selected' : '' }}>
                                👤 Karyawan — Hanya Penjualan & Laporan
                            </option>
                        </select>
                        {{-- Jika disabled, tetap kirim value via hidden input --}}
                        @if($user->id === Auth::id())
                            <input type="hidden" name="role" value="{{ $user->role }}">
                            <div class="form-text text-warning">
                                <i class="bi bi-lock me-1"></i>Tidak bisa mengubah role akun sendiri.
                            </div>
                        @endif
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <p class="small text-muted mb-3">
                        <i class="bi bi-info-circle me-1"></i>
                        Kosongkan field password jika tidak ingin mengubahnya.
                    </p>

                    {{-- Password Baru --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Password Baru</label>
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Kosongkan jika tidak diubah">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Konfirmasi Password Baru</label>
                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               placeholder="Ulangi password baru">
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan Perubahan
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