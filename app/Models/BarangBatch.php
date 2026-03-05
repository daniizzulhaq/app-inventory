<?php
// app/Models/BarangBatch.php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BarangBatch extends Model
{
    protected $table = 'barang_batch';

    protected $fillable = [
        'barang_id', 'no_batch', 'tanggal_masuk',
        'expired_date', 'stok', 'harga_beli', 'keterangan',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'expired_date'  => 'date',
    ];

    // ── Relations ──────────────────────────────────────
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // ── Accessors ──────────────────────────────────────
    public function getStatusExpiredAttribute(): string
    {
        if (!$this->expired_date) return 'no_expired';

        $daysLeft = now()->diffInDays($this->expired_date, false); // false = bisa negatif

        if ($daysLeft < 0)  return 'expired';       // sudah lewat
        if ($daysLeft <= 30) return 'warning';       // H-30
        return 'aman';
    }

    public function getHariSisaAttribute(): ?int
    {
        if (!$this->expired_date) return null;
        return (int) now()->diffInDays($this->expired_date, false);
    }

    // ── Scopes ─────────────────────────────────────────
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expired_date')
                     ->where('expired_date', '<', now()->toDateString());
    }

    public function scopeAkanExpired($query, int $hari = 30)
    {
        return $query->whereNotNull('expired_date')
                     ->whereBetween('expired_date', [
                         now()->toDateString(),
                         now()->addDays($hari)->toDateString(),
                     ]);
    }

    public function scopeAktif($query)
    {
        return $query->where('stok', '>', 0);
    }
}