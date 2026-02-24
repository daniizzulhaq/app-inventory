<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Penjualan;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        $user    = auth()->user();

        // ─── Dashboard Karyawan: hanya data penjualan ───────────────
        if ($user->isKaryawan()) {
            return $this->dashboardKaryawan($setting);
        }

        // ─── Dashboard Admin: data lengkap ──────────────────────────
        return $this->dashboardAdmin($setting);
    }

    // ================================================================
    // DASHBOARD ADMIN - semua data
    // ================================================================
    private function dashboardAdmin($setting)
    {
        $totalBarang   = Barang::count();
        $totalStok     = Barang::sum('stok_total');
        $masukHariIni  = PembelianDetail::whereDate('tanggal_masuk', today())->sum('qty_masuk');
        $keluarHariIni = DB::table('penjualan_detail')
            ->join('penjualan', 'penjualan_detail.penjualan_id', '=', 'penjualan.id')
            ->whereDate('penjualan.tanggal_penjualan', today())
            ->sum('penjualan_detail.qty');

        $stokKritis = Barang::whereColumn('stok_total', '<=', 'stok_minimum')
            ->where('stok_minimum', '>', 0)->count();

        // Grafik 6 bulan
        $bulanLabel = $grafikMasuk = $grafikKeluar = [];
        for ($i = 5; $i >= 0; $i--) {
            $b = now()->subMonths($i);
            $bulanLabel[]   = $b->format('M Y');
            $grafikMasuk[]  = PembelianDetail::whereYear('tanggal_masuk', $b->year)
                ->whereMonth('tanggal_masuk', $b->month)->sum('qty_masuk');
            $grafikKeluar[] = DB::table('penjualan_detail')
                ->join('penjualan', 'penjualan_detail.penjualan_id', '=', 'penjualan.id')
                ->whereYear('penjualan.tanggal_penjualan', $b->year)
                ->whereMonth('penjualan.tanggal_penjualan', $b->month)
                ->sum('penjualan_detail.qty');
        }

        $pembelianTerbaru = Pembelian::with('supplier')->latest()->limit(5)->get();
        $penjualanTerbaru = Penjualan::latest()->limit(5)->get();

        $topBarang = DB::table('penjualan_detail')
            ->join('barang', 'penjualan_detail.barang_id', '=', 'barang.id')
            ->select('barang.nama_barang', DB::raw('SUM(penjualan_detail.qty) as total_qty'))
            ->groupBy('barang.id', 'barang.nama_barang')
            ->orderByDesc('total_qty')->limit(5)->get();

        $barangKritis = Barang::with(['satuan', 'gudang'])
            ->whereColumn('stok_total', '<=', 'stok_minimum')
            ->where('stok_minimum', '>', 0)->limit(5)->get();

        // Ringkasan keuangan bulan ini
        $bulanIni = Penjualan::whereMonth('tanggal_penjualan', now()->month)
            ->whereYear('tanggal_penjualan', now()->year);
        $omzetBulanIni = (clone $bulanIni)->sum('total_harga');
        $labaBulanIni  = (clone $bulanIni)->sum('laba');

        return view('dashboard.admin', compact(
            'setting', 'totalBarang', 'totalStok', 'masukHariIni', 'keluarHariIni',
            'stokKritis', 'bulanLabel', 'grafikMasuk', 'grafikKeluar',
            'pembelianTerbaru', 'penjualanTerbaru', 'topBarang', 'barangKritis',
            'omzetBulanIni', 'labaBulanIni'
        ));
    }

    // ================================================================
    // DASHBOARD KARYAWAN - hanya data penjualan
    // ================================================================
    private function dashboardKaryawan($setting)
    {
        $keluarHariIni = DB::table('penjualan_detail')
            ->join('penjualan', 'penjualan_detail.penjualan_id', '=', 'penjualan.id')
            ->whereDate('penjualan.tanggal_penjualan', today())
            ->sum('penjualan_detail.qty');

        $transaksiHariIni = Penjualan::whereDate('tanggal_penjualan', today())->count();
        $omzetHariIni     = Penjualan::whereDate('tanggal_penjualan', today())->sum('total_harga');

        $omzetBulanIni = Penjualan::whereMonth('tanggal_penjualan', now()->month)
            ->whereYear('tanggal_penjualan', now()->year)->sum('total_harga');

        // Grafik penjualan 6 bulan
        $bulanLabel = $grafikKeluar = [];
        for ($i = 5; $i >= 0; $i--) {
            $b = now()->subMonths($i);
            $bulanLabel[]   = $b->format('M Y');
            $grafikKeluar[] = Penjualan::whereYear('tanggal_penjualan', $b->year)
                ->whereMonth('tanggal_penjualan', $b->month)->sum('total_harga');
        }

        $penjualanTerbaru = Penjualan::latest()->limit(8)->get();

        $topBarang = DB::table('penjualan_detail')
            ->join('barang', 'penjualan_detail.barang_id', '=', 'barang.id')
            ->select('barang.nama_barang', DB::raw('SUM(penjualan_detail.qty) as total_qty'))
            ->groupBy('barang.id', 'barang.nama_barang')
            ->orderByDesc('total_qty')->limit(5)->get();

        return view('dashboard.karyawan', compact(
            'setting', 'keluarHariIni', 'transaksiHariIni', 'omzetHariIni', 'omzetBulanIni',
            'bulanLabel', 'grafikKeluar', 'penjualanTerbaru', 'topBarang'
        ));
    }
}