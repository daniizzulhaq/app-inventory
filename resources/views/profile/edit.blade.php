@extends('layouts.app')
@section('title', 'Edit User')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pengaturan.users.index') }}" class="text-decoration-none">Kelola User</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center"><div class="col-lg-6">
<div class="card">
    <div class="card-header"><i class="bi bi-pencil me-2"></i>Edit User: {{ $user->name }}</div>
    <div class="card-body">
        <form action="{{ route('pengaturan.users.update', $user) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold small">Nama Lengkap *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold small">Email *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold small">Role *</label>
                <select name="role" class="form-select" required>
                    <option value="admin"    {{ $user->role === 'admin'    ? 'selected' : '' }}>👑 Admin — Akses penuh</option>
                    <option value="karyawan" {{ $user->role === 'karyawan' ? 'selected' : '' }}>👤 Karyawan — Hanya Penjualan & Laporan</option>
                </select>
            </div>

            <hr>
            <p class="small text-muted mb-3">Kosongkan password jika tidak ingin mengubahnya.</p>

            <div class="mb-3">
                <label class="form-label fw-semibold small">Password Baru</label>
                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold small">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('pengaturan.users.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection