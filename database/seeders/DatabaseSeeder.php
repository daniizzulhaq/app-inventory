<?php

namespace Database\Seeders;

use App\Models\Gudang;
use App\Models\Satuan;
use App\Models\Setting;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin ────────────────────────────────────────────────
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@berkahsedati.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        // ─── Karyawan contoh ──────────────────────────────────────
        User::create([
            'name'     => 'Beni',
            'email'    => 'beni@berkahsedati.com',
            'password' => Hash::make('karyawan123'),
            'role'     => 'karyawan',
        ]);

      
        // ─── Setting perusahaan ───────────────────────────────────
        Setting::create([
            'nama_perusahaan' => 'Gudang Sembako Berkah Sedati',
            'alamat'          => 'Jl. Raya Sedati No. 1, Sidoarjo, Jawa Timur',
            'telepon'         => '031-12345678',
            'email'           => 'info@berkahsedati.com',
        ]);

        // ─── Satuan ───────────────────────────────────────────────
        $satuanData = [
            ['nama_satuan' => 'Kilogram', 'singkatan' => 'Kg'],
            ['nama_satuan' => 'Gram',     'singkatan' => 'gr'],
            ['nama_satuan' => 'Liter',    'singkatan' => 'L'],
            ['nama_satuan' => 'Karung',   'singkatan' => 'krg'],
            ['nama_satuan' => 'Dus',      'singkatan' => 'dus'],
            ['nama_satuan' => 'Pcs',      'singkatan' => 'pcs'],
            ['nama_satuan' => 'Botol',    'singkatan' => 'btl'],
            ['nama_satuan' => 'Bungkus',  'singkatan' => 'bks'],
            ['nama_satuan' => 'Karton',   'singkatan' => 'ktn'],
        ];
        foreach ($satuanData as $s) Satuan::create($s);

        // ─── Gudang ───────────────────────────────────────────────
        Gudang::create(['kode_gudang' => 'GD-001', 'nama_gudang' => 'Gudang Utama',    'alamat' => 'Jl. Raya Sedati No. 1', 'penanggung_jawab' => 'Budi Santoso']);
        Gudang::create(['kode_gudang' => 'GD-002', 'nama_gudang' => 'Gudang Cadangan', 'alamat' => 'Jl. Raya Sedati No. 3', 'penanggung_jawab' => 'Siti Rahayu']);

        // ─── Supplier ─────────────────────────────────────────────
        Supplier::create(['kode_supplier' => 'SP-001', 'nama_supplier' => 'PT Sumber Makmur', 'telepon' => '031-11111111', 'email' => 'sumber@makmur.com',  'alamat' => 'Surabaya',  'kontak_person' => 'Bapak Ahmad']);
        Supplier::create(['kode_supplier' => 'SP-002', 'nama_supplier' => 'UD Berkah Jaya',   'telepon' => '031-22222222', 'email' => 'berkah@jaya.com',    'alamat' => 'Sidoarjo',  'kontak_person' => 'Ibu Sari']);
        Supplier::create(['kode_supplier' => 'SP-003', 'nama_supplier' => 'CV Maju Bersama',  'telepon' => '031-33333333', 'email' => null,                  'alamat' => 'Gresik',    'kontak_person' => 'Pak Hendra']);
    }
}