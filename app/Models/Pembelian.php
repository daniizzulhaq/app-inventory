<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $table = 'pembelian';
    protected $fillable = ['no_pembelian', 'supplier_id', 'tanggal_pembelian', 'total_harga', 'keterangan'];

    protected $casts = [
        'tanggal_pembelian' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function detail()
    {
        return $this->hasMany(PembelianDetail::class);
    }
}