@extends('layouts.app')

@section('title', 'Kelola User')

@section('breadcrumb')
    <li class="breadcrumb-item text-muted">Sistem</li>
    <li class="breadcrumb-item active">Kelola User</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="fw-bold mb-1">Kelola User</h5>
        <p class="text-muted small mb-0">Manajemen akun admin dan karyawan</p>
    </div>
    <a href="{{ route('pengaturan.users.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-person-plus me-1"></i>Tambah User
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-people me-2"></i>Daftar User ({{ $users->total() }})</span>
        <div class="d-flex gap-2 align-items-center">
            <span class="badge" style="background:#fff3cd;color:#856404;">👑 Admin: {{ $users->where('role','admin')->count() }}</span>
            <span class="badge" style="background:#d1e7dd;color:#146c43;">👤 Karyawan: {{ $users->where('role','karyawan')->count() }}</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="50" class="text-center">#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Dibuat</th>
                        <th width="110" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $user)
                    <tr>
                        <td class="text-center text-muted small">{{ $users->firstItem() + $i }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                {{-- Avatar --}}
                                <div style="
                                    width:34px; height:34px; border-radius:8px; flex-shrink:0;
                                    background:{{ $user->role === 'admin' ? '#fff3cd' : '#d1e7dd' }};
                                    display:flex; align-items:center; justify-content:center;
                                    font-weight:700; font-size:13px;
                                    color:{{ $user->role === 'admin' ? '#856404' : '#146c43' }};">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold small">{{ $user->name }}</div>
                                    @if($user->id === Auth::id())
                                        <span class="badge bg-secondary" style="font-size:9px;">Anda</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="small text-muted">{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge" style="background:#fff3cd;color:#856404;font-size:12px;">
                                    👑 Admin
                                </span>
                            @else
                                <span class="badge" style="background:#d1e7dd;color:#146c43;font-size:12px;">
                                    👤 Karyawan
                                </span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="text-center">
                            <a href="{{ route('pengaturan.users.edit', $user) }}"
                               class="btn btn-sm btn-warning"
                               title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($user->id !== Auth::id())
                            <form action="{{ route('pengaturan.users.destroy', $user) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus akun {{ addslashes($user->name) }}? Tindakan ini tidak bisa dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @else
                            <button class="btn btn-sm btn-outline-secondary" disabled title="Tidak bisa hapus akun sendiri">
                                <i class="bi bi-trash"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-people d-block fs-2 mb-2 opacity-25"></i>
                            Belum ada user terdaftar
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection