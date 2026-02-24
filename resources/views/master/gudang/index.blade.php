@extends('layouts.app')
@section('title', 'Data Gudang')
@section('breadcrumb')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">Gudang</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-building me-2"></i>Data Gudang</span>
        <a href="{{ route('master.gudang.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Tambah Gudang
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Kode</th>
                        <th>Nama Gudang</th>
                        <th>Lokasi</th>
                        <th>Jumlah Barang</th>
                        <th>Keterangan</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gudang as $i => $g)
                    <tr>
                        <td>{{ $gudang->firstItem() + $i }}</td>
                        <td><span class="badge bg-secondary">{{ $g->kode_gudang }}</span></td>
                        <td class="fw-semibold">{{ $g->nama_gudang }}</td>
                        <td>{{ $g->lokasi ?? '-' }}</td>
                        <td><span class="badge bg-info text-dark">{{ $g->barang_count }} barang</span></td>
                        <td>{{ $g->keterangan ?? '-' }}</td>
                        <td>
                            <a href="{{ route('master.gudang.edit', $g) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('master.gudang.destroy', $g) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus gudang {{ $g->nama_gudang }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada data gudang</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $gudang->links() }}
    </div>
</div>
@endsection