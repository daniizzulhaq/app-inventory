<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
     protected $table = 'pembelian_detail';
    protected $fillable = [
        'pembelian_id', 'barang_id', 'qty_masuk', 'sisa_qty',
        'harga_beli', 'subtotal', 'tanggal_masuk'
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}