@extends('layouts.app')
@section('title', 'Laporan')
@section('breadcrumb')
    <li class="breadcrumb-item active">Laporan</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-md-3">
        <a href="{{ route('laporan.stok') }}" class="text-decoration-none">
            <div class="card h-100 border-primary">
                <div class="card-body text-center py-4">
                    <i class="bi bi-boxes fs-1 text-primary mb-3 d-block"></i>
                    <h5 class="fw-semibold text-primary">Laporan Stok</h5>
                    <p class="text-muted small mb-0">Ringkasan stok barang saat ini</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('laporan.barang-masuk') }}" class="text-decoration-none">
            <div class="card h-100 border-success">
                <div class="card-body text-center py-4">
                    <i class="bi bi-box-arrow-in-down fs-1 text-success mb-3 d-block"></i>
                    <h5 class="fw-semibold text-success">Barang Masuk</h5>
                    <p class="text-muted small mb-0">Riwayat pembelian & barang masuk</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('laporan.barang-keluar') }}" class="text-decoration-none">
            <div class="card h-100 border-warning">
                <div class="card-body text-center py-4">
                    <i class="bi bi-box-arrow-up fs-1 text-warning mb-3 d-block"></i>
                    <h5 class="fw-semibold text-warning">Barang Keluar</h5>
                    <p class="text-muted small mb-0">Riwayat penjualan & barang keluar</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('laporan.laba-rugi') }}" class="text-decoration-none">
            <div class="card h-100 border-info">
                <div class="card-body text-center py-4">
                    <i class="bi bi-graph-up-arrow fs-1 text-info mb-3 d-block"></i>
                    <h5 class="fw-semibold text-info">Laba Rugi</h5>
                    <p class="text-muted small mb-0">Rekap laba rugi per periode</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection