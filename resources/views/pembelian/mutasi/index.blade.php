@extends('layouts.app')
@section('title', 'Mutasi Barang')
@section('breadcrumb')
    <li class="breadcrumb-item">Pembelian</li>
    <li class="breadcrumb-item active">Mutasi</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-arrow-left-right me-2"></i>Data Mutasi Barang</span>
        <a href="{{ route('pembelian.mutasi.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Tambah Mutasi
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>No. Mutasi</th>
                        <th>Barang</th>
                        <th>Gudang Asal</th>
                        <th>Gudang Tujuan</th>
                        <th>Qty</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mutasi as $i => $m)
                    <tr>
                        <td>{{ $mutasi->firstItem() + $i }}</td>
                        <td><span class="badge bg-secondary">{{ $m->no_mutasi }}</span></td>
                        <td class="fw-semibold">{{ $m->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $m->gudangAsal->nama_gudang ?? '-' }}</td>
                        <td>{{ $m->gudangTujuan->nama_gudang ?? '-' }}</td>
                        <td>{{ $m->qty }}</td>
                        <td>{{ \Carbon\Carbon::parse($m->tanggal_mutasi)->format('d/m/Y') }}</td>
                        <td>{{ $m->keterangan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data mutasi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $mutasi->links() }}
    </div>
</div>
@endsection