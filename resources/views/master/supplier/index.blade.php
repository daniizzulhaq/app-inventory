@extends('layouts.app')
@section('title', 'Data Supplier')
@section('breadcrumb')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">Supplier</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-truck me-2"></i>Data Supplier</span>
        <a href="{{ route('master.supplier.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Tambah Supplier
        </a>
    </div>
    <div class="card-body">
        <!-- Search -->
        <form method="GET" class="mb-3">
            <div class="input-group" style="max-width:320px">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Cari kode/nama supplier..." value="{{ request('search') }}">
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Kode</th>
                        <th>Nama Supplier</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($supplier as $i => $s)
                    <tr>
                        <td>{{ $supplier->firstItem() + $i }}</td>
                        <td><span class="badge bg-secondary">{{ $s->kode_supplier }}</span></td>
                        <td class="fw-semibold">{{ $s->nama_supplier }}</td>
                        <td>{{ $s->telepon ?? '-' }}</td>
                        <td>{{ $s->email ?? '-' }}</td>
                        <td>{{ $s->alamat ?? '-' }}</td>
                        <td>
                            <a href="{{ route('master.supplier.edit', $s) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('master.supplier.destroy', $s) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus supplier {{ $s->nama_supplier }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada data supplier</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $supplier->links() }}
    </div>
</div>
@endsection