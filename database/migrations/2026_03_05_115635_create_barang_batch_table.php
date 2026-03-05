<?php
// database/migrations/xxxx_create_barang_batch_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_batch', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade');
            $table->string('no_batch')->nullable();          // nomor batch/lot
            $table->date('tanggal_masuk');
            $table->date('expired_date')->nullable();
            $table->integer('stok')->default(0);
            $table->decimal('harga_beli', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['barang_id', 'expired_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_batch');
    }
};