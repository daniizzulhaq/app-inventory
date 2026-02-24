<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->foreignId('satuan_id')->constrained('satuan')->onDelete('restrict');
            $table->foreignId('gudang_id')->constrained('gudang')->onDelete('restrict');
            $table->integer('stok_total')->default(0);
            $table->integer('stok_minimum')->default(0);
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};