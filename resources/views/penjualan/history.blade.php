@extends('layouts.app')
@section('title', 'History Penjualan')
@section('breadcrumb')
    <li class="breadcrumb-item">Penjualan</li>
    <li class="breadcrumb-item active">History</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-clock-history me-2"></i>History Penjualan
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-2">
                <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('penjualan.history') }}" class="btn btn-sm btn-outline-danger">Reset</a>
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
                        <th width="80">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualan as $i => $p)
                    <tr>
                        <td>{{ $penjualan->firstItem() + $i }}</td>
                        <td><span class="badge bg-success">{{ $p->no_invoice }}</span></td>
                        <td>{{ $p->nama_pembeli ?? '-' }}</td>
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
                        <td>
                            <a href="{{ route('penjualan.keluar.show', $p) }}" class="btn btn-sm btn-info text-white">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada history penjualan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $penjualan->links() }}
    </div>
</div>
@endsection