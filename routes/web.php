<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangBatchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ============================================================
    // ADMIN + KARYAWAN : Penjualan & Laporan
    // ============================================================
    Route::middleware(['role:admin,karyawan'])->group(function () {

        Route::prefix('penjualan')->name('penjualan.')->group(function () {
           Route::get('keluar',                  [PenjualanController::class, 'keluarIndex'])->name('keluar.index');
            Route::get('keluar/create',           [PenjualanController::class, 'keluarCreate'])->name('keluar.create');
            Route::post('keluar',                 [PenjualanController::class, 'keluarStore'])->name('keluar.store');
            Route::get('keluar/{penjualan}',      [PenjualanController::class, 'keluarShow'])->name('keluar.show');
            Route::delete('keluar/{penjualan}',   [PenjualanController::class, 'keluarDestroy'])->name('keluar.destroy'); // ← TAMBAH
            Route::get('history',                 [PenjualanController::class, 'historyIndex'])->name('history');
        });

        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/',              [LaporanController::class, 'index'])->name('index');
            Route::get('stok',          [LaporanController::class, 'stok'])->name('stok');
            Route::get('barang-masuk',  [LaporanController::class, 'barangMasuk'])->name('barang-masuk');
            Route::get('barang-keluar', [LaporanController::class, 'barangKeluar'])->name('barang-keluar');
            Route::get('laba-rugi',     [LaporanController::class, 'labaRugi'])->name('laba-rugi');
        });
    });

    // ============================================================
    // ADMIN ONLY : Master, Pembelian, Pengaturan
    // ============================================================
    Route::middleware(['role:admin'])->group(function () {

        // Master Data
        Route::prefix('master')->name('master.')->group(function () {

            // Barang (full resource termasuk show)
            Route::resource('barang', BarangController::class);
            Route::get('barang/{barang}/stok', [BarangController::class, 'getStok'])
                 ->name('barang.stok');

            // Monitor expired — HARUS di atas nested resource barang.batch
            Route::get('barang-batch/monitor', [BarangBatchController::class, 'monitor'])
                 ->name('barang.batch.monitor');

            // Batch nested resource (tanpa ->names() agar prefix master. otomatis)
            Route::resource('barang.batch', BarangBatchController::class)
                 ->except(['show']);

            Route::resource('supplier', SupplierController::class)->except(['show']);
            Route::resource('gudang',   GudangController::class)->except(['show']);
            Route::resource('satuan',   SatuanController::class)->except(['show']);
        });

        // Pembelian
       Route::prefix('pembelian')->name('pembelian.')->group(function () {
    Route::get('masuk',                       [PembelianController::class, 'masukIndex'])->name('masuk.index');
    Route::get('masuk/create',                [PembelianController::class, 'masukCreate'])->name('masuk.create');
    Route::post('masuk',                      [PembelianController::class, 'masukStore'])->name('masuk.store');
    Route::get('masuk/{pembelian}',           [PembelianController::class, 'masukShow'])->name('masuk.show');
    Route::get('masuk/{pembelian}/edit',      [PembelianController::class, 'masukEdit'])->name('masuk.edit');   // ← TAMBAH
    Route::put('masuk/{pembelian}',           [PembelianController::class, 'masukUpdate'])->name('masuk.update'); // ← TAMBAH
    Route::delete('masuk/{pembelian}',        [PembelianController::class, 'masukDestroy'])->name('masuk.destroy');

    Route::get('mutasi',                      [PembelianController::class, 'mutasiIndex'])->name('mutasi.index');
    Route::get('mutasi/create',               [PembelianController::class, 'mutasiCreate'])->name('mutasi.create');
    Route::post('mutasi',                     [PembelianController::class, 'mutasiStore'])->name('mutasi.store');

    Route::get('history',                     [PembelianController::class, 'historyIndex'])->name('history');
});

        // Pengaturan
        Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
            Route::get('/',    [PengaturanController::class, 'index'])->name('index');
            Route::post('/',   [PengaturanController::class, 'updateSetting'])->name('update');

            Route::get('users',             [PengaturanController::class, 'userIndex'])->name('users.index');
            Route::get('users/create',      [PengaturanController::class, 'userCreate'])->name('users.create');
            Route::post('users',            [PengaturanController::class, 'userStore'])->name('users.store');
            Route::get('users/{user}/edit', [PengaturanController::class, 'userEdit'])->name('users.edit');
            Route::put('users/{user}',      [PengaturanController::class, 'userUpdate'])->name('users.update');
            Route::delete('users/{user}',   [PengaturanController::class, 'userDestroy'])->name('users.destroy');
        });
    });

    // ============================================================
    // SEMUA ROLE : Profil
    // ============================================================
    Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
        Route::get('profil',         [PengaturanController::class, 'profil'])->name('profil');
        Route::put('profil',         [PengaturanController::class, 'updateProfil'])->name('profil.update');
        Route::put('ganti-password', [PengaturanController::class, 'gantiPassword'])->name('ganti-password');
    });

    Route::get('/profile', [PengaturanController::class, 'profil'])->name('profile.edit');
});

require __DIR__.'/auth.php';