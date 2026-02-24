<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// ============================================================
// PROTECTED ROUTES (LOGIN WAJIB)
// ============================================================
Route::middleware(['auth'])->group(function () {

    // Dashboard - semua role bisa akses (isinya beda per role, dikontrol di controller)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ============================================================
    // ADMIN + KARYAWAN : Penjualan & Laporan
    // ============================================================
    Route::middleware(['role:admin,karyawan'])->group(function () {

        // Penjualan
        Route::prefix('penjualan')->name('penjualan.')->group(function () {
            Route::get('keluar',             [PenjualanController::class, 'keluarIndex'])->name('keluar.index');
            Route::get('keluar/create',      [PenjualanController::class, 'keluarCreate'])->name('keluar.create');
            Route::post('keluar',            [PenjualanController::class, 'keluarStore'])->name('keluar.store');
            Route::get('keluar/{penjualan}', [PenjualanController::class, 'keluarShow'])->name('keluar.show');
            Route::get('history',            [PenjualanController::class, 'historyIndex'])->name('history');
        });

        // Laporan
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/',              [LaporanController::class, 'index'])->name('index');
            Route::get('stok',          [LaporanController::class, 'stok'])->name('stok');
            Route::get('barang-masuk',  [LaporanController::class, 'barangMasuk'])->name('barang-masuk');
            Route::get('barang-keluar', [LaporanController::class, 'barangKeluar'])->name('barang-keluar');
            Route::get('laba-rugi',     [LaporanController::class, 'labaRugi'])->name('laba-rugi');
        });
    });

    // ============================================================
    // ADMIN ONLY : Master, Pembelian, Pengaturan Perusahaan
    // ============================================================
    Route::middleware(['role:admin'])->group(function () {

        // Master Data
        Route::prefix('master')->name('master.')->group(function () {
            Route::resource('barang',   BarangController::class)->except(['show']);
            Route::get('barang/{barang}/stok', [BarangController::class, 'getStok'])->name('barang.stok');
            Route::resource('supplier', SupplierController::class)->except(['show']);
            Route::resource('gudang',   GudangController::class)->except(['show']);
            Route::resource('satuan',   SatuanController::class)->except(['show']);
        });

        // Pembelian
        Route::prefix('pembelian')->name('pembelian.')->group(function () {
            Route::get('masuk',                [PembelianController::class, 'masukIndex'])->name('masuk.index');
            Route::get('masuk/create',         [PembelianController::class, 'masukCreate'])->name('masuk.create');
            Route::post('masuk',               [PembelianController::class, 'masukStore'])->name('masuk.store');
            Route::get('masuk/{pembelian}',    [PembelianController::class, 'masukShow'])->name('masuk.show');
            Route::delete('masuk/{pembelian}', [PembelianController::class, 'masukDestroy'])->name('masuk.destroy');

            Route::get('mutasi',               [PembelianController::class, 'mutasiIndex'])->name('mutasi.index');
            Route::get('mutasi/create',        [PembelianController::class, 'mutasiCreate'])->name('mutasi.create');
            Route::post('mutasi',              [PembelianController::class, 'mutasiStore'])->name('mutasi.store');

            Route::get('history',              [PembelianController::class, 'historyIndex'])->name('history');
        });

        // Pengaturan Perusahaan
        Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
            Route::get('/',    [PengaturanController::class, 'index'])->name('index');
            Route::post('/',   [PengaturanController::class, 'updateSetting'])->name('update');

            // Manajemen User (tambah/edit/hapus akun karyawan)
            Route::get('users',              [PengaturanController::class, 'userIndex'])->name('users.index');
            Route::get('users/create',       [PengaturanController::class, 'userCreate'])->name('users.create');
            Route::post('users',             [PengaturanController::class, 'userStore'])->name('users.store');
            Route::get('users/{user}/edit',  [PengaturanController::class, 'userEdit'])->name('users.edit');
            Route::put('users/{user}',       [PengaturanController::class, 'userUpdate'])->name('users.update');
            Route::delete('users/{user}',    [PengaturanController::class, 'userDestroy'])->name('users.destroy');
        });
    });

    // ============================================================
    // SEMUA ROLE : Profil Sendiri
    // ============================================================
    Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
        Route::get('profil',          [PengaturanController::class, 'profil'])->name('profil');
        Route::put('profil',          [PengaturanController::class, 'updateProfil'])->name('profil.update');
        Route::put('ganti-password',  [PengaturanController::class, 'gantiPassword'])->name('ganti-password');
    });

    // Alias /profile (Breeze compatibility)
    Route::get('/profile', [PengaturanController::class, 'profil'])->name('profile.edit');
});

// ============================================================
// AUTH ROUTES (login, logout - dari Breeze)
// ============================================================
require __DIR__.'/auth.php';