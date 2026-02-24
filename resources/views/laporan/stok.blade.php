@extends('layouts.app')
@section('title', 'Laporan Stok')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('laporan.index') }}" class="text-decoration-none">Laporan</a></li>
    <li class="breadcrumb-item active">Stok</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-boxes me-2"></i>Laporan Stok Barang</span>
        <a href="{{ route('laporan.stok', ['export' => 'pdf']) }}" target="_blank" class="btn btn-sm btn-danger">
            <i class="bi bi-file-pdf me-1"></i>Export PDF
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Gudang</th>
                        <th>Stok</th>
                        <th>Min Stok</th>
                        <th>Harga Jual</th>
                        <th>Nilai Stok</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barang as $i => $b)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><span class="badge bg-secondary">{{ $b->kode_barang }}</span></td>
                        <td class="fw-semibold">{{ $b->nama_barang }}</td>
                        <td>{{ $b->satuan->nama_satuan ?? '-' }}</td>
                        <td>{{ $b->gudang->nama_gudang ?? '-' }}</td>
                        <td>{{ $b->stok_total }}</td>
                        <td>{{ $b->stok_minimum }}</td>
                        <td>Rp {{ number_format($b->harga_jual) }}</td>
                        <td>Rp {{ number_format($b->stok_total * $b->harga_jual) }}</td>
                        <td>
                            @if($b->stok_total == 0)
                                <span class="badge bg-danger">Habis</span>
                            @elseif($b->stok_total <= $b->stok_minimum && $b->stok_minimum > 0)
                                <span class="badge bg-warning text-dark">Menipis</span>
                            @else
                                <span class="badge bg-success">Aman</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center py-4 text-muted">Belum ada data barang</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="fw-bold table-light">
                        <td colspan="8" class="text-end">Total Nilai Stok</td>
                        <td>Rp {{ number_format($barang->sum(fn($b) => $b->stok_total * $b->harga_jual)) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection