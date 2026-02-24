@extends('layouts.app')
@section('title', 'Laporan Barang Masuk')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('laporan.index') }}" class="text-decoration-none">Laporan</a></li>
    <li class="breadcrumb-item active">Barang Masuk</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-box-arrow-in-down me-2"></i>Laporan Barang Masuk</span>
        <a href="{{ route('laporan.barang-masuk', array_merge(request()->all(), ['export' => 'pdf'])) }}"
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

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Tanggal</th>
                        <th>No. Pembelian</th>
                        <th>Supplier</th>
                        <th>Barang</th>
                        <th>Satuan</th>
                        <th>Qty Masuk</th>
                        <th>Harga Beli</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $d)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $d->tanggal_masuk->format('d/m/Y') }}</td>
                        <td><span class="badge bg-primary">{{ $d->pembelian->no_pembelian ?? '-' }}</span></td>
                        <td>{{ $d->pembelian->supplier->nama_supplier ?? '-' }}</td>
                        <td class="fw-semibold">{{ $d->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $d->barang->satuan->nama_satuan ?? '-' }}</td>
                        <td>{{ $d->qty_masuk }}</td>
                        <td>Rp {{ number_format($d->harga_beli) }}</td>
                        <td>Rp {{ number_format($d->subtotal) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">Tidak ada data pada periode ini</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="fw-bold table-light">
                        <td colspan="8" class="text-end">Total</td>
                        <td>Rp {{ number_format($total) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection