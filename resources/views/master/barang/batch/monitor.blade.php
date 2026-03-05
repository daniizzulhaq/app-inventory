@extends('layouts.app')
@section('title', 'Monitor Expired')
@section('breadcrumb')
    <li class="breadcrumb-item active">Monitor Expired</li>
@endsection

@section('content')
{{-- Summary cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-danger text-center py-3">
            <div class="display-6 fw-bold text-danger">{{ $countExpired }}</div>
            <div class="text-muted small">Batch Sudah Expired</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-warning text-center py-3">
            <div class="display-6 fw-bold text-warning">{{ $countAkanExpired }}</div>
            <div class="text-muted small">Akan Expired (≤30 hari)</div>
        </div>
    </div>
</div>

{{-- Filter tabs --}}
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $filter === 'akan_expired' ? 'active' : '' }}"
                   href="{{ request()->fullUrlWithQuery(['filter' => 'akan_expired']) }}">
                    Akan Expired
                    @if($countAkanExpired > 0)
                        <span class="badge bg-warning text-dark ms-1">{{ $countAkanExpired }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $filter === 'expired' ? 'active' : '' }}"
                   href="{{ request()->fullUrlWithQuery(['filter' => 'expired']) }}">
                    Sudah Expired
                    @if($countExpired > 0)
                        <span class="badge bg-danger ms-1">{{ $countExpired }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $filter === 'semua' ? 'active' : '' }}"
                   href="{{ request()->fullUrlWithQuery(['filter' => 'semua']) }}">Semua Batch</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Barang</th>
                        <th>No Batch</th>
                        <th>Expired</th>
                        <th>Sisa Hari</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batches as $b)
                    @php
                        $status = $b->status_expired;
                        $rowClass = match($status) {
                            'expired' => 'table-danger',
                            'warning' => 'table-warning',
                            default   => '',
                        };
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td>
                            <div class="fw-semibold">{{ $b->barang->nama_barang }}</div>
                            <small class="text-muted">{{ $b->barang->kode_barang }}</small>
                        </td>
                        <td><span class="badge bg-secondary">{{ $b->no_batch ?? '-' }}</span></td>
                        <td>{{ $b->expired_date?->format('d/m/Y') ?? '—' }}</td>
                        <td>
                            @if($b->hari_sisa === null)
                                <span class="text-muted">—</span>
                            @elseif($b->hari_sisa < 0)
                                <span class="text-danger fw-bold">Lewat {{ abs($b->hari_sisa) }} hari</span>
                            @else
                                <span class="{{ $b->hari_sisa <= 30 ? 'text-warning fw-bold' : '' }}">
                                    {{ $b->hari_sisa }} hari
                                </span>
                            @endif
                        </td>
                        <td>{{ number_format($b->stok) }} {{ $b->barang->satuan->singkatan ?? '' }}</td>
                        <td>
                            @if($status === 'expired')
                                <span class="badge bg-danger">Expired</span>
                            @elseif($status === 'warning')
                                <span class="badge bg-warning text-dark">Segera Expired</span>
                            @else
                                <span class="badge bg-success">Aman</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('master.barang.batch.index', $b->barang) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Lihat Batch
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $batches->links() }}
    </div>
</div>
@endsection