@extends('layouts.app')
@section('title', 'Data Satuan')
@section('breadcrumb')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">Satuan</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-rulers me-2"></i>Data Satuan</span>
        <a href="{{ route('master.satuan.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Tambah Satuan
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Satuan</th>
                        <th>Singkatan</th>
                        <th>Jumlah Barang</th>
                        <th>Keterangan</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($satuan as $i => $s)
                    <tr>
                        <td>{{ $satuan->firstItem() + $i }}</td>
                        <td class="fw-semibold">{{ $s->nama_satuan }}</td>
                        <td><span class="badge bg-secondary">{{ $s->singkatan ?? '-' }}</span></td>
                        <td><span class="badge bg-info text-dark">{{ $s->barang_count }} barang</span></td>
                        <td>{{ $s->keterangan ?? '-' }}</td>
                        <td>
                            <a href="{{ route('master.satuan.edit', $s) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('master.satuan.destroy', $s) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus satuan {{ $s->nama_satuan }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data satuan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $satuan->links() }}
    </div>
</div>
@endsection