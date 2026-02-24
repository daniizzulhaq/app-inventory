<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $fillable = [
        'kode_barang', 'nama_barang', 'satuan_id', 'gudang_id',
        'stok_total', 'stok_minimum', 'harga_jual', 'keterangan'
    ];

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function pembelianDetail()
    {
        return $this->hasMany(PembelianDetail::class);
    }

    public function penjualanDetail()
    {
        return $this->hasMany(PenjualanDetail::class);
    }

    // Ambil batch FIFO (stok tertua dengan sisa_qty > 0)
    public function fifoBatches()
    {
        return $this->hasMany(PembelianDetail::class)
            ->where('sisa_qty', '>', 0)
            ->orderBy('tanggal_masuk', 'asc')
            ->orderBy('id', 'asc');
    }
}