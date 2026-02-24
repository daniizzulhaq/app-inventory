<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mutasi', function (Blueprint $table) {
            $table->id();
            $table->string('no_mutasi')->unique();
            $table->foreignId('barang_id')->constrained('barang')->onDelete('restrict');
            $table->foreignId('gudang_asal_id')->constrained('gudang')->onDelete('restrict');
            $table->foreignId('gudang_tujuan_id')->constrained('gudang')->onDelete('restrict');
            $table->integer('qty');
            $table->date('tanggal_mutasi');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi');
    }
};