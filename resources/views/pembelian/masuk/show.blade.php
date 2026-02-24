@extends('layouts.app')
@section('title', 'Detail Pembelian')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pembelian.masuk.index') }}" class="text-decoration-none">Barang Masuk</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-file-text me-2"></i>Detail Pembelian: {{ $pembelian->no_pembelian }}</span>
                <a href="{{ route('pembelian.masuk.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><td class="fw-semibold" width="140">No. Pembelian</td><td>: <span class="badge bg-primary">{{ $pembelian->no_pembelian }}</span></td></tr>
                            <tr><td class="fw-semibold">Supplier</td><td>: {{ $pembelian->supplier->nama_supplier ?? '-' }}</td></tr>
                            <tr><td class="fw-semibold">Tanggal</td><td>: {{ $pembelian->tanggal_pembelian->format('d/m/Y') }}</td></tr>
                            <tr><td class="fw-semibold">Keterangan</td><td>: {{ $pembelian->keterangan ?? '-' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h5 class="fw-bold text-primary">Total: Rp {{ number_format($pembelian->total_harga) }}</h5>
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
                                <th>Qty Masuk</th>
                                <th>Sisa Stok</th>
                                <th>Harga Beli</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pembelian->detail as $i => $d)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="fw-semibold">{{ $d->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $d->barang->satuan->nama_satuan ?? '-' }}</td>
                                <td>{{ $d->qty_masuk }}</td>
                                <td>
                                    @if($d->sisa_qty == 0)
                                        <span class="badge bg-danger">Habis</span>
                                    @elseif($d->sisa_qty < $d->qty_masuk)
                                        <span class="badge bg-warning text-dark">{{ $d->sisa_qty }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $d->sisa_qty }}</span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($d->harga_beli) }}</td>
                                <td>Rp {{ number_format($d->subtotal) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" class="text-end fw-bold">Total</td>
                                <td class="fw-bold">Rp {{ number_format($pembelian->total_harga) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection