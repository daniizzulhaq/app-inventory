<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\PembelianDetail;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function stok(Request $request)
    {
        $barang = Barang::with(['satuan', 'gudang'])
            ->orderBy('nama_barang')->get();

        if ($request->export === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pdf.stok', compact('barang'));
            return $pdf->stream('laporan-stok-' . date('Ymd') . '.pdf');
        }

        return view('laporan.stok', compact('barang'));
    }

    public function barangMasuk(Request $request)
    {
        $dari   = $request->dari ?? now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? now()->format('Y-m-d');

        $data = PembelianDetail::with(['pembelian.supplier', 'barang.satuan'])
            ->whereDate('tanggal_masuk', '>=', $dari)
            ->whereDate('tanggal_masuk', '<=', $sampai)
            ->orderBy('tanggal_masuk')
            ->get();

        $total = $data->sum('subtotal');

        if ($request->export === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pdf.barang_masuk',
                compact('data', 'total', 'dari', 'sampai')
            )->setPaper('a4', 'landscape');
            return $pdf->stream('laporan-barang-masuk-' . date('Ymd') . '.pdf');
        }

        return view('laporan.barang_masuk', compact('data', 'total', 'dari', 'sampai'));
    }

    public function barangKeluar(Request $request)
    {
        $dari   = $request->dari ?? now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? now()->format('Y-m-d');

        $data = Penjualan::with('detail.barang.satuan')
            ->whereDate('tanggal_penjualan', '>=', $dari)
            ->whereDate('tanggal_penjualan', '<=', $sampai)
            ->orderBy('tanggal_penjualan')
            ->get();

        $totalHarga = $data->sum('total_harga');
        $totalHpp   = $data->sum('total_hpp');
        $totalLaba  = $data->sum('laba');

        if ($request->export === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pdf.barang_keluar',
                compact('data', 'totalHarga', 'totalHpp', 'totalLaba', 'dari', 'sampai')
            )->setPaper('a4', 'landscape');
            return $pdf->stream('laporan-barang-keluar-' . date('Ymd') . '.pdf');
        }

        return view('laporan.barang_keluar', compact('data', 'totalHarga', 'totalHpp', 'totalLaba', 'dari', 'sampai'));
    }

    public function labaRugi(Request $request)
    {
        $dari   = $request->dari ?? now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? now()->format('Y-m-d');

        $penjualan = Penjualan::whereDate('tanggal_penjualan', '>=', $dari)
            ->whereDate('tanggal_penjualan', '<=', $sampai);

        $totalPenjualan = $penjualan->sum('total_harga');
        $totalHpp       = $penjualan->sum('total_hpp');
        $totalLaba      = $penjualan->sum('laba');

        $perBarang = DB::table('penjualan_detail')
            ->join('barang', 'penjualan_detail.barang_id', '=', 'barang.id')
            ->join('penjualan', 'penjualan_detail.penjualan_id', '=', 'penjualan.id')
            ->whereDate('penjualan.tanggal_penjualan', '>=', $dari)
            ->whereDate('penjualan.tanggal_penjualan', '<=', $sampai)
            ->select(
                'barang.nama_barang',
                DB::raw('SUM(penjualan_detail.qty) as total_qty'),
                DB::raw('SUM(penjualan_detail.subtotal) as total_penjualan'),
                DB::raw('SUM(penjualan_detail.hpp) as total_hpp'),
                DB::raw('SUM(penjualan_detail.laba) as total_laba')
            )
            ->groupBy('barang.id', 'barang.nama_barang')
            ->orderByDesc('total_laba')
            ->get();

        if ($request->export === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pdf.laba_rugi',
                compact('totalPenjualan', 'totalHpp', 'totalLaba', 'perBarang', 'dari', 'sampai')
            );
            return $pdf->stream('laporan-laba-rugi-' . date('Ymd') . '.pdf');
        }

        return view('laporan.laba_rugi', compact(
            'totalPenjualan', 'totalHpp', 'totalLaba', 'perBarang', 'dari', 'sampai'
        ));
    }
}