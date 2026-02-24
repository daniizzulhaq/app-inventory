@extends('layouts.app')
@section('title', 'Laporan Barang Keluar')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('laporan.index') }}" class="text-decoration-none">Laporan</a></li>
    <li class="breadcrumb-item active">Barang Keluar</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-box-arrow-up me-2"></i>Laporan Barang Keluar</span>
        <a href="{{ route('laporan.barang-keluar', array_merge(request()->all(), ['export' => 'pdf'])) }}"
           class="btn btn-sm btn-danger">
            <i class="bi bi-file-pdf me-1"></i>Export PDF
        </a>
    </div>
    <div class="card-body">
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

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Tanggal</th>
                        <th>No. Invoice</th>
                        <th>Nama Pembeli</th>
                        <th>Total Harga</th>
                        <th>Total HPP</th>
                        <th>Laba</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $p)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tanggal_penjualan)->format('d/m/Y') }}</td>
                        <td><span class="badge bg-success">{{ $p->no_invoice }}</span></td>
                        <td>{{ $p->nama_pembeli ?? '-' }}</td>
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
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada data pada periode ini</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="fw-bold table-light">
                        <td colspan="4" class="text-end">Total</td>
                        <td>Rp {{ number_format($totalHarga) }}</td>
                        <td>Rp {{ number_format($totalHpp) }}</td>
                        <td class="{{ $totalLaba >= 0 ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($totalLaba) }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection