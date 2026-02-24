<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Inventory Berkah Sedati</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --primary:       #1634e0;
            --primary-dark:  #0f26b8;
            --primary-light: #e8efff;
        }

        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; }

        /* ── SIDEBAR ─────────────────── */
        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-dark) 0%, var(--primary) 100%);
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            transition: all .3s;
            overflow-y: auto;
        }

        #sidebar .sidebar-brand {
            padding: 18px 16px;
            border-bottom: 1px solid rgba(255,255,255,.15);
        }

        #sidebar .sidebar-brand h5 { color:#fff; font-weight:700; font-size:14px; margin:0; line-height:1.3; }

        /* Role badge di sidebar */
        .sidebar-role-badge {
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-top: 4px;
        }
        .sidebar-role-badge.admin    { background: rgba(255,200,0,.2); color: #ffd700; }
        .sidebar-role-badge.karyawan { background: rgba(0,255,150,.15); color: #5fffc0; }

        #sidebar .nav-item { padding: 2px 10px; }
        #sidebar .nav-link {
            color: rgba(255,255,255,.75);
            border-radius: 8px;
            padding: 9px 14px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all .2s;
        }
        #sidebar .nav-link:hover,
        #sidebar .nav-link.active { color:#fff; background:rgba(255,255,255,.15); }
        #sidebar .nav-link i { font-size:16px; min-width:20px; }

        #sidebar .menu-label {
            color: rgba(255,255,255,.4);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 12px 24px 4px;
        }

        #sidebar .collapse-menu .nav-link { padding-left:44px; font-size:13px; }
        #sidebar .collapse-toggle { justify-content:space-between; }
        #sidebar .collapse-toggle .arrow { font-size:11px; transition:transform .2s; }
        #sidebar .collapse-toggle[aria-expanded="true"] .arrow { transform:rotate(90deg); }

        /* ── MAIN ────────────────────── */
        #main-content { margin-left:var(--sidebar-width); min-height:100vh; transition:all .3s; }

        .top-navbar {
            background:#fff;
            box-shadow:0 2px 8px rgba(0,0,0,.08);
            padding:0 24px;
            height:60px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            position:sticky; top:0; z-index:999;
        }

        .page-content { padding:24px; }

        /* Role badge di topbar */
        .role-badge-top {
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            text-transform: uppercase;
        }
        .role-badge-top.admin    { background:#fff3cd; color:#856404; }
        .role-badge-top.karyawan { background:#d1e7dd; color:#146c43; }

        /* Cards */
        .stat-card { border-radius:12px; border:none; box-shadow:0 2px 12px rgba(0,0,0,.06); }
        .card { border-radius:10px; border:1px solid #e9ecef; box-shadow:0 1px 4px rgba(0,0,0,.04); }
        .card-header { background:#fff; border-bottom:1px solid #e9ecef; font-weight:600; padding:14px 18px; }

        .table th { font-size:13px; font-weight:600; color:#555; }
        .table td { font-size:14px; vertical-align:middle; }

        .btn-primary { background:var(--primary); border-color:var(--primary); }
        .btn-primary:hover { background:var(--primary-dark); border-color:var(--primary-dark); }

        .alert { border-radius:8px; }

        @media (max-width:768px) {
            #sidebar { transform:translateX(-100%); }
            #sidebar.show { transform:translateX(0); }
            #main-content { margin-left:0; }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- ── SIDEBAR ───────────────────────────────────────────── -->
<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2 mb-1">
            <div style="width:36px;height:36px;background:rgba(255,255,255,.2);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-box-seam text-white fs-5"></i>
            </div>
            <div>
                <h5>Berkah Sedati</h5>
                <small style="color:rgba(255,255,255,.5);font-size:11px;">Inventory System</small>
            </div>
        </div>
        {{-- Nama & Role User --}}
        <div class="mt-2 ps-1">
            <div style="color:#fff;font-size:12.5px;font-weight:600;">{{ Auth::user()->name }}</div>
            <span class="sidebar-role-badge {{ Auth::user()->role }}">
                {{ Auth::user()->role === 'admin' ? '👑 Admin' : '👤 Karyawan' }}
            </span>
        </div>
    </div>

    <ul class="nav flex-column mt-2">

        {{-- Dashboard --}}
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>

        {{-- ─────── ADMIN ONLY ─────── --}}
        @if(Auth::user()->isAdmin())

            <p class="menu-label">Master Data</p>
            <li class="nav-item">
                <a href="#menuMaster" class="nav-link collapse-toggle {{ request()->is('master/*') ? 'active' : '' }}"
                   data-bs-toggle="collapse" aria-expanded="{{ request()->is('master/*') ? 'true' : 'false' }}">
                    <span><i class="bi bi-database"></i> Master Data</span>
                    <i class="bi bi-chevron-right arrow"></i>
                </a>
                <div class="collapse {{ request()->is('master/*') ? 'show' : '' }}" id="menuMaster">
                    <ul class="nav flex-column collapse-menu">
                        <li><a href="{{ route('master.barang.index') }}" class="nav-link {{ request()->routeIs('master.barang.*') ? 'active' : '' }}">
                            <i class="bi bi-box2"></i> Data Barang</a></li>
                        <li><a href="{{ route('master.supplier.index') }}" class="nav-link {{ request()->routeIs('master.supplier.*') ? 'active' : '' }}">
                            <i class="bi bi-truck"></i> Data Supplier</a></li>
                        <li><a href="{{ route('master.gudang.index') }}" class="nav-link {{ request()->routeIs('master.gudang.*') ? 'active' : '' }}">
                            <i class="bi bi-building"></i> Data Gudang</a></li>
                        <li><a href="{{ route('master.satuan.index') }}" class="nav-link {{ request()->routeIs('master.satuan.*') ? 'active' : '' }}">
                            <i class="bi bi-rulers"></i> Data Satuan</a></li>
                    </ul>
                </div>
            </li>

        @endif
        {{-- ─────── END ADMIN ONLY ─────── --}}

        {{-- Transaksi - semua role bisa lihat Penjualan --}}
        <p class="menu-label">Transaksi</p>

        {{-- Pembelian: admin only --}}
        @if(Auth::user()->isAdmin())
        <li class="nav-item">
            <a href="#menuPembelian" class="nav-link collapse-toggle {{ request()->is('pembelian/*') ? 'active' : '' }}"
               data-bs-toggle="collapse" aria-expanded="{{ request()->is('pembelian/*') ? 'true' : 'false' }}">
                <span><i class="bi bi-cart-plus"></i> Pembelian</span>
                <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="collapse {{ request()->is('pembelian/*') ? 'show' : '' }}" id="menuPembelian">
                <ul class="nav flex-column collapse-menu">
                    <li><a href="{{ route('pembelian.masuk.index') }}" class="nav-link {{ request()->routeIs('pembelian.masuk.*') ? 'active' : '' }}">
                        <i class="bi bi-box-arrow-in-down"></i> Barang Masuk</a></li>
                    <li><a href="{{ route('pembelian.mutasi.index') }}" class="nav-link {{ request()->routeIs('pembelian.mutasi.*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-left-right"></i> Mutasi</a></li>
                    <li><a href="{{ route('pembelian.history') }}" class="nav-link {{ request()->routeIs('pembelian.history') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> History</a></li>
                </ul>
            </div>
        </li>
        @endif

        {{-- Penjualan: admin + karyawan --}}
        <li class="nav-item">
            <a href="#menuPenjualan" class="nav-link collapse-toggle {{ request()->is('penjualan/*') ? 'active' : '' }}"
               data-bs-toggle="collapse" aria-expanded="{{ request()->is('penjualan/*') ? 'true' : 'false' }}">
                <span><i class="bi bi-cart-check"></i> Penjualan</span>
                <i class="bi bi-chevron-right arrow"></i>
            </a>
            <div class="collapse {{ request()->is('penjualan/*') ? 'show' : '' }}" id="menuPenjualan">
                <ul class="nav flex-column collapse-menu">
                    <li><a href="{{ route('penjualan.keluar.index') }}" class="nav-link {{ request()->routeIs('penjualan.keluar.*') ? 'active' : '' }}">
                        <i class="bi bi-box-arrow-up-right"></i> Barang Keluar</a></li>
                    <li><a href="{{ route('penjualan.history') }}" class="nav-link {{ request()->routeIs('penjualan.history') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> History</a></li>
                </ul>
            </div>
        </li>

        {{-- Laporan: admin + karyawan --}}
        <p class="menu-label">Laporan</p>
        <li class="nav-item">
            <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph"></i> Laporan
            </a>
        </li>

        {{-- Sistem: admin only --}}
        @if(Auth::user()->isAdmin())
        <p class="menu-label">Sistem</p>
        <li class="nav-item">
            <a href="{{ route('pengaturan.index') }}" class="nav-link {{ request()->routeIs('pengaturan.index') || request()->routeIs('pengaturan.update') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> Pengaturan
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('pengaturan.users.index') }}" class="nav-link {{ request()->routeIs('pengaturan.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Kelola User
            </a>
        </li>
        @endif

        {{-- Profil: semua role --}}
        @if(Auth::user()->isKaryawan())
        <p class="menu-label">Akun</p>
        @endif
        <li class="nav-item {{ Auth::user()->isAdmin() ? '' : 'mt-1' }}">
            <a href="{{ route('pengaturan.profil') }}" class="nav-link {{ request()->routeIs('pengaturan.profil') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> Profil Saya
            </a>
        </li>

        {{-- Logout --}}
        <li class="nav-item mt-1">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link border-0 w-100 text-start" style="background:none;color:rgba(255,100,100,.8);">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</nav>

<!-- ── MAIN CONTENT ──────────────────────────────────────── -->
<div id="main-content">
    <div class="top-navbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle">
                <i class="bi bi-list fs-5"></i>
            </button>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Home</a></li>
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-3">
            {{-- Role badge --}}
            <span class="role-badge-top {{ Auth::user()->role }} d-none d-md-inline">
                {{ Auth::user()->role === 'admin' ? '👑 Admin' : '👤 Karyawan' }}
            </span>
            <small class="text-muted d-none d-lg-block">{{ now()->isoFormat('dddd, D MMMM Y') }}</small>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('pengaturan.profil') }}">
                        <i class="bi bi-person me-2"></i>Profil Saya</a></li>
                    @if(Auth::user()->isAdmin())
                    <li><a class="dropdown-item" href="{{ route('pengaturan.index') }}">
                        <i class="bi bi-gear me-2"></i>Pengaturan</a></li>
                    <li><a class="dropdown-item" href="{{ route('pengaturan.users.index') }}">
                        <i class="bi bi-people me-2"></i>Kelola User</a></li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- PAGE CONTENT -->
    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
document.getElementById('sidebarToggle')?.addEventListener('click', function () {
    document.getElementById('sidebar').classList.toggle('show');
});
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', { style:'currency', currency:'IDR', minimumFractionDigits:0 }).format(angka);
}
</script>

@stack('scripts')
</body>
</html>