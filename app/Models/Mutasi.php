<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    protected $table = 'mutasi';
    protected $fillable = [
        'no_mutasi', 'barang_id', 'gudang_asal_id', 'gudang_tujuan_id',
        'qty', 'tanggal_mutasi', 'keterangan'
    ];

    protected $casts = [
        'tanggal_mutasi' => 'date',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function gudangAsal()
    {
        return $this->belongsTo(Gudang::class, 'gudang_asal_id');
    }

    public function gudangTujuan()
    {
        return $this->belongsTo(Gudang::class, 'gudang_tujuan_id');
    }
}