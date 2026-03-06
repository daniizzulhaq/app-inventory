<?php

// Tambahkan baris berikut ke dalam routes/api.php (atau routes/web.php jika tidak pakai Sanctum)

use App\Http\Controllers\PenjualanController;

// API: Preview expired FIFO untuk form penjualan
Route::get('/api/barang/{barang}/expired-fifo', [PenjualanController::class, 'getExpiredFifo']);