@extends('layouts.app')
@section('title', 'Detail Barang')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('master.barang.index') }}">Barang</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
{{-- Info Barang --}}
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-box2 me-2"></i>Info Barang</div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr><td class="fw-semibold" width="150">Kode Barang</td><td>: <span class="badge bg-secondary">{{ $barang->kode_barang }}</span></td></tr>
                    <tr><td class="fw-semibold">Nama Barang</td><td>: {{ $barang->nama_barang }}</td></tr>
                    <tr><td class="fw-semibold">Satuan</td><td>: {{ $barang->satuan->nama_satuan ?? '-' }}</td></tr>
                    <tr><td class="fw-semibold">Gudang</td><td>: {{ $barang->gudang->nama_gudang ?? '-' }}</td></tr>
                    <tr><td class="fw-semibold">Harga Jual</td><td>: Rp {{ number_format($barang->harga_jual) }}</td></tr>
                    <tr><td class="fw-semibold">Stok Total</td><td>:
                        @if($barang->stok_total <= $barang->stok_minimum && $barang->stok_minimum > 0)
                            <span class="badge bg-danger">{{ $barang->stok_total }}</span>
                        @else
                            <span class="badge bg-success">{{ $barang->stok_total }}</span>
                        @endif
                    </td></tr>
                    <tr><td class="fw-semibold">Stok Minimum</td><td>: {{ $barang->stok_minimum }}</td></tr>
                    @if($barang->keterangan)
                    <tr><td class="fw-semibold">Keterangan</td><td>: {{ $barang->keterangan }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-pie-chart me-2"></i>Ringkasan Batch</div>
            <div class="card-body">
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between align-items-center p-2 rounded bg-danger bg-opacity-10">
                        <span class="text-danger fw-semibold"><i class="bi bi-exclamation-triangle-fill me-1"></i>Expired</span>
                        <span class="badge bg-danger">{{ $grouped['expired']->count() }} batch</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-2 rounded bg-warning bg-opacity-10">
                        <span class="text-warning fw-semibold"><i class="bi bi-clock me-1"></i>Segera Expired</span>
                        <span class="badge bg-warning text-dark">{{ $grouped['warning']->count() }} batch</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-2 rounded bg-success bg-opacity-10">
                        <span class="text-success fw-semibold"><i class="bi bi-check-circle me-1"></i>Aman</span>
                        <span class="badge bg-success">{{ $grouped['aman']->count() }} batch</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-2 rounded bg-secondary bg-opacity-10">
                        <span class="text-secondary fw-semibold"><i class="bi bi-dash-circle me-1"></i>Tanpa Expired</span>
                        <span class="badge bg-secondary">{{ $grouped['no_expired']->count() }} batch</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Kelompok: Expired --}}
@if($grouped['expired']->count())
<div class="card mb-3 border-danger">
    <div class="card-header bg-danger text-white">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>Batch Sudah Expired
        <span class="badge bg-white text-danger ms-2">{{ $grouped['expired']->count() }}</span>
    </div>
    <div class="card-body p-0">
        @include('master.barang._batch_table', ['batches' => $grouped['expired'], 'rowClass' => 'table-danger'])
    </div>
</div>
@endif

{{-- Kelompok: Segera Expired --}}
@if($grouped['warning']->count())
<div class="card mb-3 border-warning">
    <div class="card-header bg-warning text-dark">
        <i class="bi bi-clock me-2"></i>Segera Expired (≤30 hari)
        <span class="badge bg-dark text-warning ms-2">{{ $grouped['warning']->count() }}</span>
    </div>
    <div class="card-body p-0">
        @include('master.barang._batch_table', ['batches' => $grouped['warning'], 'rowClass' => 'table-warning'])
    </div>
</div>
@endif

{{-- Kelompok: Aman --}}
@if($grouped['aman']->count())
<div class="card mb-3 border-success">
    <div class="card-header bg-success text-white">
        <i class="bi bi-check-circle me-2"></i>Aman
        <span class="badge bg-white text-success ms-2">{{ $grouped['aman']->count() }}</span>
    </div>
    <div class="card-body p-0">
        @include('master.barang._batch_table', ['batches' => $grouped['aman'], 'rowClass' => ''])
    </div>
</div>
@endif

{{-- Kelompok: Tanpa Expired --}}
@if($grouped['no_expired']->count())
<div class="card mb-3">
    <div class="card-header bg-secondary text-white">
        <i class="bi bi-dash-circle me-2"></i>Tanpa Expired
        <span class="badge bg-white text-secondary ms-2">{{ $grouped['no_expired']->count() }}</span>
    </div>
    <div class="card-body p-0">
        @include('master.barang._batch_table', ['batches' => $grouped['no_expired'], 'rowClass' => ''])
    </div>
</div>
@endif

@if($semuaBatch->isEmpty())
<div class="card">
    <div class="card-body text-center py-5 text-muted">
        <i class="bi bi-inbox display-4"></i>
        <p class="mt-2">Belum ada batch untuk barang ini</p>
        <a href="{{ route('master.barang.batch.create', $barang) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Tambah Batch
        </a>
    </div>
</div>
@endif

<div class="mt-3">
    <a href="{{ route('master.barang.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <a href="{{ route('master.barang.batch.index', $barang) }}" class="btn btn-outline-primary ms-2">
        <i class="bi bi-layers me-1"></i>Kelola Semua Batch
    </a>
</div>
@endsection