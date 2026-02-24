@extends('layouts.app')
@section('title', 'Detail Penjualan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('penjualan.keluar.index') }}" class="text-decoration-none">Barang Keluar</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-file-text me-2"></i>Detail Penjualan: {{ $penjualan->no_invoice }}</span>
                <a href="{{ route('penjualan.keluar.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><td class="fw-semibold" width="140">No. Invoice</td><td>: <span class="badge bg-success">{{ $penjualan->no_invoice }}</span></td></tr>
                            <tr><td class="fw-semibold">Nama Pembeli</td><td>: {{ $penjualan->nama_pembeli ?? '-' }}</td></tr>
                            <tr><td class="fw-semibold">Tanggal</td><td>: {{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d/m/Y') }}</td></tr>
                            <tr><td class="fw-semibold">Keterangan</td><td>: {{ $penjualan->keterangan ?? '-' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><td class="fw-semibold" width="120">Total Harga</td><td>: <span class="fw-bold text-primary">Rp {{ number_format($penjualan->total_harga) }}</span></td></tr>
                            <tr><td class="fw-semibold">Total HPP</td><td>: Rp {{ number_format($penjualan->total_hpp) }}</td></tr>
                            <tr><td class="fw-semibold">Laba</td>
                                <td>:
                                    @if($penjualan->laba >= 0)
                                        <span class="fw-bold text-success">Rp {{ number_format($penjualan->laba) }}</span>
                                    @else
                                        <span class="fw-bold text-danger">Rp {{ number_format($penjualan->laba) }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <h6 class="fw-semibold mb-2"><i class="bi bi-list-ul me-1"></i>Detail Barang</h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Qty</th>
                                <th>Harga Jual</th>
                                <th>HPP</th>
                                <th>Subtotal</th>
                                <th>Laba</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penjualan->detail as $i => $d)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="fw-semibold">{{ $d->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $d->barang->satuan->nama_satuan ?? '-' }}</td>
                                <td>{{ $d->qty }}</td>
                                <td>Rp {{ number_format($d->harga_jual) }}</td>
                                <td>Rp {{ number_format($d->hpp) }}</td>
                                <td>Rp {{ number_format($d->subtotal) }}</td>
                                <td>
                                    @if($d->laba >= 0)
                                        <span class="text-success">Rp {{ number_format($d->laba) }}</span>
                                    @else
                                        <span class="text-danger">Rp {{ number_format($d->laba) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td colspan="6" class="text-end">Total</td>
                                <td>Rp {{ number_format($penjualan->total_harga) }}</td>
                                <td class="{{ $penjualan->laba >= 0 ? 'text-success' : 'text-danger' }}">
                                    Rp {{ number_format($penjualan->laba) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection