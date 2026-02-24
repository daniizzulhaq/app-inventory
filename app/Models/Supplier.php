<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $fillable = ['kode_supplier', 'nama_supplier', 'telepon', 'email', 'alamat', 'kontak_person'];

    public function pembelian()
    {
        return $this->hasMany(Pembelian::class);
    }
}