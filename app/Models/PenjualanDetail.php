<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
     protected $table = 'penjualan_detail';
    protected $fillable = [
        'penjualan_id', 'barang_id', 'qty', 'harga_jual', 'hpp', 'subtotal', 'laba'
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
