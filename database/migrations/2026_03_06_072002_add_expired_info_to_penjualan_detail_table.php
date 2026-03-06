<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penjualan_detail', function (Blueprint $table) {
            // Simpan info expired batch yang dipilih saat penjualan
            // Format JSON: [{"expired_date":"28/12/2026","qty":1,"status":"aman"}, ...]
            $table->json('expired_info')->nullable()->after('laba');
        });
    }

    public function down(): void
    {
        Schema::table('penjualan_detail', function (Blueprint $table) {
            $table->dropColumn('expired_info');
        });
    }
};