@extends('layouts.app')
@section('title', 'Laporan Laba Rugi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('laporan.index') }}" class="text-decoration-none">Laporan</a></li>
    <li class="breadcrumb-item active">Laba Rugi</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-graph-up-arrow me-2"></i>Laporan Laba Rugi</span>
        <a href="{{ route('laporan.laba-rugi', array_merge(request()->all(), ['export' => 'pdf'])) }}"
           class="btn btn-sm btn-danger">
            <i class="bi bi-file-pdf me-1"></i>Export PDF
        </a>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form method="GET" class="row g-2 mb-3">
            <div class="col-auto">
                <label class="col-form-label col-form-label-sm">Dari</label>
            </div>
            <div class="col-md-2">
                <input type="date" name="dari" class="form-control form-control-sm" value="{{ $dari }}">
            </div>
            <div class="col-auto">
                <label class="col-form-label col-form-label-sm">Sampai</label>
            </div>
            <div class="col-md-2">
                <input type="date" name="sampai" class="form-control form-control-sm" value="{{ $sampai }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i> Tampilkan</button>
            </div>
        </form>

        <div class="alert alert-info py-2">
            Periode: <strong>{{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }}</strong> s/d
            <strong>{{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}</strong>
        </div>

        <!-- Ringkasan -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="small opacity-75">Total Penjualan</div>
                        <div class="fs-5 fw-bold">Rp {{ number_format($totalPenjualan) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <div class="small opacity-75">Total HPP</div>
                        <div class="fs-5 fw-bold">Rp {{ number_format($totalHpp) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card {{ $totalLaba >= 0 ? 'bg-success' : 'bg-danger' }} text-white">
                    <div class="card-body">
                        <div class="small opacity-75">{{ $totalLaba >= 0 ? 'Total Laba' : 'Total Rugi' }}</div>
                        <div class="fs-5 fw-bold">Rp {{ number_format($totalLaba) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Per Barang -->
        <h6 class="fw-semibold mb-2"><i class="bi bi-list-ul me-1"></i>Rincian Per Barang</h6>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Barang</th>
                        <th>Total Qty Terjual</th>
                        <th>Total Penjualan</th>
                        <th>Total HPP</th>
                        <th>Laba</th>
                        <th>Margin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perBarang as $i => $b)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="fw-semibold">{{ $b->nama_barang }}</td>
                        <td>{{ $b->total_qty }}</td>
                        <td>Rp {{ number_format($b->total_penjualan) }}</td>
                        <td>Rp {{ number_format($b->total_hpp) }}</td>
                        <td>
                            @if($b->total_laba >= 0)
                                <span class="text-success fw-semibold">Rp {{ number_format($b->total_laba) }}</span>
                            @else
                                <span class="text-danger fw-semibold">Rp {{ number_format($b->total_laba) }}</span>
                            @endif
                        </td>
                        <td>
                            @php $margin = $b->total_penjualan > 0 ? ($b->total_laba / $b->total_penjualan * 100) : 0; @endphp
                            <span class="{{ $margin >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($margin, 1) }}%
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data pada periode ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection