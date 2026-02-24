<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    protected $fillable = [
        'no_invoice', 'nama_pembeli', 'tanggal_penjualan',
        'total_harga', 'total_hpp', 'laba', 'keterangan'
    ];

    protected $casts = [
        'tanggal_penjualan' => 'date',
    ];

    public function detail()
    {
        return $this->hasMany(PenjualanDetail::class);
    }
}