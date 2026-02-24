<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique();
            $table->string('nama_pembeli')->nullable();
            $table->date('tanggal_penjualan');
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->decimal('total_hpp', 15, 2)->default(0);
            $table->decimal('laba', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};