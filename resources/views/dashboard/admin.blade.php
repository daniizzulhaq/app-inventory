@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">Dashboard Admin</h4>
        <p class="text-muted mb-0 small">{{ $setting->nama_perusahaan ?? 'Berkah Sedati' }} — Semua data tersedia</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pembelian.masuk.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Barang Masuk
        </a>
        <a href="{{ route('penjualan.keluar.create') }}" class="btn btn-sm btn-success">
            <i class="bi bi-plus-circle me-1"></i>Barang Keluar
        </a>
    </div>
</div>

{{-- Stok kritis alert --}}
@if($stokKritis > 0)
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <strong>Peringatan!</strong> Ada <strong>{{ $stokKritis }}</strong> barang dengan stok di bawah minimum.
    <a href="{{ route('master.barang.index') }}" class="alert-link">Lihat →</a>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card" style="border-top:3px solid #1634e0;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Total Barang</p>
                        <h3 class="fw-bold mb-0" style="color:#1634e0;">{{ number_format($totalBarang) }}</h3>
                        <small class="text-muted">jenis barang</small>
                    </div>
                    <div style="width:40px;height:40px;background:#e8efff;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-box2" style="color:#1634e0;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card" style="border-top:3px solid #0d6efd;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Total Stok</p>
                        <h3 class="fw-bold mb-0 text-primary">{{ number_format($totalStok) }}</h3>
                        <small class="text-muted">unit tersedia</small>
                    </div>
                    <div style="width:40px;height:40px;background:#e8f0fe;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-archive text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card" style="border-top:3px solid #198754;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Omzet Bulan Ini</p>
                        <h3 class="fw-bold mb-0 text-success" style="font-size:18px;">Rp {{ number_format($omzetBulanIni) }}</h3>
                        <small class="text-muted">total penjualan</small>
                    </div>
                    <div style="width:40px;height:40px;background:#d1e7dd;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-cash-coin text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card" style="border-top:3px solid #ffc107;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Laba Bulan Ini</p>
                        <h3 class="fw-bold mb-0 text-warning" style="font-size:18px;">Rp {{ number_format($labaBulanIni) }}</h3>
                        <small class="text-muted">laba bersih</small>
                    </div>
                    <div style="width:40px;height:40px;background:#fff3cd;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-graph-up-arrow text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- GRAFIK + TOP BARANG --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-bar-chart-line me-2 text-primary"></i>Grafik Stok 6 Bulan Terakhir</div>
            <div class="card-body"><canvas id="chartStok" style="max-height:220px;"></canvas></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-trophy me-2" style="color:#f0a500;"></i>Top 5 Terlaris</div>
            <div class="card-body p-0">
                @forelse($topBarang as $i => $b)
                <div class="d-flex align-items-center gap-2 px-3 py-2 border-bottom">
                    <span class="badge rounded-circle d-flex align-items-center justify-content-center"
                        style="width:24px;height:24px;background:#1634e0;font-size:11px;">{{ $i+1 }}</span>
                    <span class="flex-grow-1 text-truncate small">{{ $b->nama_barang }}</span>
                    <span class="badge bg-light text-dark">{{ number_format($b->total_qty) }}</span>
                </div>
                @empty
                <div class="text-center text-muted py-4 small">Belum ada penjualan</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- TABEL BAWAH --}}
<div class="row g-3">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-exclamation-circle me-2 text-warning"></i>Stok Kritis</span>
                <a href="{{ route('master.barang.index') }}" class="btn btn-sm btn-outline-warning" style="font-size:11px;">Lihat</a>
            </div>
            <table class="table table-sm mb-0">
                <thead class="table-light"><tr><th>Barang</th><th>Stok</th><th>Min</th></tr></thead>
                <tbody>
                    @forelse($barangKritis as $b)
                    <tr>
                        <td class="small">{{ $b->nama_barang }}</td>
                        <td><span class="badge bg-danger">{{ $b->stok_total }}</span></td>
                        <td class="text-muted small">{{ $b->stok_minimum }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-3 text-muted small"><i class="bi bi-check-circle text-success me-1"></i>Semua stok aman</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-cart-plus me-2 text-primary"></i>Pembelian Terbaru</span>
                <a href="{{ route('pembelian.masuk.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:11px;">Lihat</a>
            </div>
            <table class="table table-sm mb-0">
                <thead class="table-light"><tr><th>No.</th><th>Supplier</th><th>Tanggal</th><th>Total</th></tr></thead>
                <tbody>
                    @forelse($pembelianTerbaru as $p)
                    <tr>
                        <td><a href="{{ route('pembelian.masuk.show', $p) }}" class="text-decoration-none small fw-semibold">{{ $p->no_pembelian }}</a></td>
                        <td class="small">{{ $p->supplier->nama_supplier ?? '-' }}</td>
                        <td class="text-muted small">{{ $p->tanggal_pembelian->format('d/m/Y') }}</td>
                        <td class="small fw-semibold">Rp {{ number_format($p->total_harga) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-3 text-muted small">Belum ada pembelian</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
new Chart(document.getElementById('chartStok').getContext('2d'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($bulanLabel) !!},
        datasets: [
            { label:'Barang Masuk',  data:{!! json_encode($grafikMasuk) !!},  backgroundColor:'rgba(22,52,224,.8)',  borderRadius:5 },
            { label:'Barang Keluar', data:{!! json_encode($grafikKeluar) !!}, backgroundColor:'rgba(220,53,69,.75)', borderRadius:5 }
        ]
    },
    options:{
        responsive:true, maintainAspectRatio:true,
        plugins:{ legend:{position:'bottom', labels:{font:{size:12}, usePointStyle:true}} },
        scales:{ y:{beginAtZero:true, ticks:{precision:0}}, x:{grid:{display:false}} }
    }
});
</script>
@endpush