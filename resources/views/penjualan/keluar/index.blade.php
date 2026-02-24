@extends('layouts.app')
@section('title', 'Barang Keluar')
@section('breadcrumb')
    <li class="breadcrumb-item">Penjualan</li>
    <li class="breadcrumb-item active">Barang Keluar</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-box-arrow-up me-2"></i>Data Barang Keluar</span>
        <a href="{{ route('penjualan.keluar.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Tambah Penjualan
        </a>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Cari no. invoice / pembeli..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('penjualan.keluar.index') }}" class="btn btn-sm btn-outline-danger">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>No. Invoice</th>
                        <th>Nama Pembeli</th>
                        <th>Tanggal</th>
                        <th>Total Harga</th>
                        <th>Total HPP</th>
                        <th>Laba</th>
                        <th>Keterangan</th>
                        <th width="80">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualan as $i => $p)
                    <tr>
                        <td>{{ $penjualan->firstItem() + $i }}</td>
                        <td><span class="badge bg-success">{{ $p->no_invoice }}</span></td>
                        <td class="fw-semibold">{{ $p->nama_pembeli ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tanggal_penjualan)->format('d/m/Y') }}</td>
                        <td>Rp {{ number_format($p->total_harga) }}</td>
                        <td>Rp {{ number_format($p->total_hpp) }}</td>
                        <td>
                            @if($p->laba >= 0)
                                <span class="text-success fw-semibold">Rp {{ number_format($p->laba) }}</span>
                            @else
                                <span class="text-danger fw-semibold">Rp {{ number_format($p->laba) }}</span>
                            @endif
                        </td>
                        <td>{{ $p->keterangan ?? '-' }}</td>
                        <td>
                            <a href="{{ route('penjualan.keluar.show', $p) }}" class="btn btn-sm btn-info text-white">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">Belum ada data penjualan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $penjualan->links() }}
    </div>
</div>
@endsection