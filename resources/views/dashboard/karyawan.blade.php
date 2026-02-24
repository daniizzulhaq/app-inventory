@extends('layouts.app')
@section('title', 'Dashboard')
@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">
            Selamat datang, {{ Auth::user()->name }}! 👋
        </h4>
        <p class="text-muted mb-0 small">
            {{ $setting->nama_perusahaan ?? 'Berkah Sedati' }} — Panel Karyawan
        </p>
    </div>
    <a href="{{ route('penjualan.keluar.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>Catat Penjualan
    </a>
</div>

{{-- INFO BANNER KARYAWAN --}}
<div class="alert alert-info d-flex align-items-center gap-2 mb-4" style="border-radius:10px;">
    <i class="bi bi-info-circle-fill flex-shrink-0"></i>
    <div class="small">
        Anda login sebagai <strong>Karyawan</strong>. Akses tersedia: <strong>Penjualan</strong> dan <strong>Laporan</strong>.
        Untuk akses penuh, hubungi Administrator.
    </div>
</div>

{{-- STAT CARDS PENJUALAN --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card" style="border-top:3px solid #198754;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Transaksi Hari Ini</p>
                        <h3 class="fw-bold mb-0 text-success">{{ number_format($transaksiHariIni) }}</h3>
                        <small class="text-muted">transaksi</small>
                    </div>
                    <div style="width:40px;height:40px;background:#d1e7dd;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-receipt text-success"></i>
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
                        <p class="text-muted mb-1" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Unit Terjual Hari Ini</p>
                        <h3 class="fw-bold mb-0 text-primary">{{ number_format($keluarHariIni) }}</h3>
                        <small class="text-muted">unit</small>
                    </div>
                    <div style="width:40px;height:40px;background:#e8f0fe;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-box-arrow-up-right text-primary"></i>
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
                        <p class="text-muted mb-1" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Omzet Hari Ini</p>
                        <h4 class="fw-bold mb-0 text-warning" style="font-size:16px;">Rp {{ number_format($omzetHariIni) }}</h4>
                        <small class="text-muted">total penjualan</small>
                    </div>
                    <div style="width:40px;height:40px;background:#fff3cd;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-cash text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card" style="border-top:3px solid #20c997;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Omzet Bulan Ini</p>
                        <h4 class="fw-bold mb-0" style="color:#20c997;font-size:16px;">Rp {{ number_format($omzetBulanIni) }}</h4>
                        <small class="text-muted">bulan ini</small>
                    </div>
                    <div style="width:40px;height:40px;background:#d1f2eb;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-calendar-check" style="color:#20c997;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- GRAFIK PENJUALAN + TOP BARANG --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-graph-up me-2 text-success"></i>Grafik Omzet Penjualan (6 Bulan)</div>
            <div class="card-body"><canvas id="chartPenjualan" style="max-height:220px;"></canvas></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-trophy me-2" style="color:#f0a500;"></i>Top 5 Terlaris</div>
            <div class="card-body p-0">
                @forelse($topBarang as $i => $b)
                <div class="d-flex align-items-center gap-2 px-3 py-2 border-bottom">
                    <span class="badge rounded-circle d-flex align-items-center justify-content-center"
                        style="width:24px;height:24px;background:#198754;font-size:11px;">{{ $i+1 }}</span>
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

{{-- PENJUALAN TERBARU --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-cart-check me-2 text-success"></i>Penjualan Terbaru</span>
        <a href="{{ route('penjualan.keluar.index') }}" class="btn btn-sm btn-outline-success" style="font-size:12px;">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-sm mb-0">
            <thead class="table-light">
                <tr><th>No Invoice</th><th>Pembeli</th><th>Tanggal</th><th>Total</th><th>Laba</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($penjualanTerbaru as $p)
                <tr>
                    <td class="fw-semibold small" style="color:#198754;">{{ $p->no_invoice }}</td>
                    <td class="small">{{ $p->nama_pembeli ?: '-' }}</td>
                    <td class="text-muted small">{{ $p->tanggal_penjualan->format('d/m/Y') }}</td>
                    <td class="small fw-semibold">Rp {{ number_format($p->total_harga) }}</td>
                    <td>
                        <span class="badge {{ $p->laba >= 0 ? 'bg-success' : 'bg-danger' }}">
                            Rp {{ number_format($p->laba) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('penjualan.keluar.show', $p) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px;">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted small">
                    <i class="bi bi-inbox d-block fs-3 mb-2 opacity-25"></i>Belum ada transaksi penjualan
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- SHORTCUT LAPORAN --}}
<div class="row g-2 mt-3">
    <div class="col-12"><small class="text-muted fw-semibold text-uppercase" style="letter-spacing:1px;">Akses Cepat Laporan</small></div>
    <div class="col-6 col-md-3">
        <a href="{{ route('laporan.barang-keluar') }}" class="card text-decoration-none text-center p-3 h-100" style="border:1.5px dashed #dee2e6;border-radius:10px;">
            <i class="bi bi-file-earmark-bar-graph d-block fs-3 mb-1 text-success"></i>
            <span class="small">Laporan Keluar</span>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('laporan.laba-rugi') }}" class="card text-decoration-none text-center p-3 h-100" style="border:1.5px dashed #dee2e6;border-radius:10px;">
            <i class="bi bi-graph-up-arrow d-block fs-3 mb-1 text-warning"></i>
            <span class="small">Laba Rugi</span>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('penjualan.history') }}" class="card text-decoration-none text-center p-3 h-100" style="border:1.5px dashed #dee2e6;border-radius:10px;">
            <i class="bi bi-clock-history d-block fs-3 mb-1 text-info"></i>
            <span class="small">History Penjualan</span>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('laporan.stok') }}" class="card text-decoration-none text-center p-3 h-100" style="border:1.5px dashed #dee2e6;border-radius:10px;">
            <i class="bi bi-boxes d-block fs-3 mb-1 text-primary"></i>
            <span class="small">Laporan Stok</span>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
new Chart(document.getElementById('chartPenjualan').getContext('2d'), {
    type: 'line',
    data: {
        labels: {!! json_encode($bulanLabel) !!},
        datasets: [{
            label: 'Omzet Penjualan (Rp)',
            data: {!! json_encode($grafikKeluar) !!},
            borderColor: '#198754',
            backgroundColor: 'rgba(25,135,84,.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#198754',
            pointRadius: 4,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + Intl.NumberFormat('id-ID').format(v) } },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush