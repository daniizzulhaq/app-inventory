@extends('layouts.app')
@section('title', 'Detail Penjualan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('penjualan.keluar.index') }}" class="text-decoration-none">Barang Keluar</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@push('styles')
<style>
    @page {
        margin: 0;
        size: A4;
    }
    @media print {
        body * {
            visibility: hidden;
        }
        #faktur-print, #faktur-print * {
            visibility: visible;
        }
        #faktur-print {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-file-text me-2"></i>Detail Penjualan: {{ $penjualan->no_invoice }}</span>
                <div class="d-flex gap-2">
                    <button onclick="cetakFaktur()" class="btn btn-sm btn-success no-print">
                        <i class="bi bi-printer me-1"></i>Cetak Faktur
                    </button>
                    <a href="{{ route('penjualan.keluar.index') }}" class="btn btn-sm btn-outline-secondary no-print">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><td class="fw-semibold" width="140">No. Invoice</td><td>: <span class="badge bg-success">{{ $penjualan->no_invoice }}</span></td></tr>
                            <tr><td class="fw-semibold">Nama Pembeli</td><td>: {{ $penjualan->nama_pembeli ?? '-' }}</td></tr>
                            <tr><td class="fw-semibold">Tanggal</td><td>: {{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d/m/Y') }}</td></tr>
                            <tr><td class="fw-semibold">Keterangan</td><td>: {{ $penjualan->keterangan ?? '-' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><td class="fw-semibold" width="120">Total Harga</td><td>: <span class="fw-bold text-primary">Rp {{ number_format($penjualan->total_harga) }}</span></td></tr>
                            <tr><td class="fw-semibold">Total HPP</td><td>: Rp {{ number_format($penjualan->total_hpp) }}</td></tr>
                            <tr><td class="fw-semibold">Laba</td>
                                <td>:
                                    @if($penjualan->laba >= 0)
                                        <span class="fw-bold text-success">Rp {{ number_format($penjualan->laba) }}</span>
                                    @else
                                        <span class="fw-bold text-danger">Rp {{ number_format($penjualan->laba) }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <h6 class="fw-semibold mb-2"><i class="bi bi-list-ul me-1"></i>Detail Barang</h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Qty</th>
                                <th>Harga Jual</th>
                                <th>HPP</th>
                                <th>Subtotal</th>
                                <th>Laba</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penjualan->detail as $i => $d)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="fw-semibold">{{ $d->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $d->barang->satuan->nama_satuan ?? '-' }}</td>
                                <td>{{ $d->qty }}</td>
                                <td>Rp {{ number_format($d->harga_jual) }}</td>
                                <td>Rp {{ number_format($d->hpp) }}</td>
                                <td>Rp {{ number_format($d->subtotal) }}</td>
                                <td>
                                    @if($d->laba >= 0)
                                        <span class="text-success">Rp {{ number_format($d->laba) }}</span>
                                    @else
                                        <span class="text-danger">Rp {{ number_format($d->laba) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td colspan="6" class="text-end">Total</td>
                                <td>Rp {{ number_format($penjualan->total_harga) }}</td>
                                <td class="{{ $penjualan->laba >= 0 ? 'text-success' : 'text-danger' }}">
                                    Rp {{ number_format($penjualan->laba) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== AREA FAKTUR PRINT ===== --}}
<div id="faktur-print" style="display:none;">
    <div style="font-family: Arial, sans-serif; max-width: 800px; margin: auto; padding: 40px 50px;">

        {{-- Header --}}
        <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 20px;">
            <div>
                <h2 style="margin:0; font-size: 22px;">Berkah Sedati</h2>
                <p style="margin:4px 0; font-size: 12px; color: #555;">Alamat Perusahaan, Kota, Kode Pos</p>
                <p style="margin:4px 0; font-size: 12px; color: #555;">Telp: (021) 0000-0000 | Email: info@perusahaan.com</p>
            </div>
            <div style="text-align: right;">
                <h3 style="margin:0; font-size: 20px; color: #333;">FAKTUR PENJUALAN</h3>
                <p style="margin:4px 0; font-size: 13px;"><strong>No:</strong> {{ $penjualan->no_invoice }}</p>
                <p style="margin:4px 0; font-size: 13px;"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d/m/Y') }}</p>
            </div>
        </div>

        {{-- Info Pembeli --}}
        <div style="margin-bottom: 20px;">
            <table style="font-size: 13px; width: 50%;">
                <tr>
                    <td style="padding: 3px 0; width: 130px;"><strong>Kepada</strong></td>
                    <td>: {{ $penjualan->nama_pembeli ?? '-' }}</td>
                </tr>
                @if(!empty($penjualan->keterangan))
                <tr>
                    <td style="padding: 3px 0;"><strong>Keterangan</strong></td>
                    <td>: {{ $penjualan->keterangan }}</td>
                </tr>
                @endif
            </table>
        </div>

        {{-- Tabel Detail Barang --}}
        <table style="width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 20px;">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th style="border: 1px solid #ccc; padding: 8px; text-align: center; width: 40px;">No</th>
                    <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Nama Barang</th>
                    <th style="border: 1px solid #ccc; padding: 8px; text-align: center;">Satuan</th>
                    <th style="border: 1px solid #ccc; padding: 8px; text-align: center;">Qty</th>
                    <th style="border: 1px solid #ccc; padding: 8px; text-align: right;">Harga Jual</th>
                    <th style="border: 1px solid #ccc; padding: 8px; text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penjualan->detail as $i => $d)
                <tr>
                    <td style="border: 1px solid #ccc; padding: 7px; text-align: center;">{{ $i + 1 }}</td>
                    <td style="border: 1px solid #ccc; padding: 7px;">{{ $d->barang->nama_barang ?? '-' }}</td>
                    <td style="border: 1px solid #ccc; padding: 7px; text-align: center;">{{ $d->barang->satuan->nama_satuan ?? '-' }}</td>
                    <td style="border: 1px solid #ccc; padding: 7px; text-align: center;">{{ $d->qty }}</td>
                    <td style="border: 1px solid #ccc; padding: 7px; text-align: right;">Rp {{ number_format($d->harga_jual) }}</td>
                    <td style="border: 1px solid #ccc; padding: 7px; text-align: right;">Rp {{ number_format($d->subtotal) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" style="border: 1px solid #ccc; padding: 8px; text-align: right; font-weight: bold;">TOTAL</td>
                    <td style="border: 1px solid #ccc; padding: 8px; text-align: right; font-weight: bold;">Rp {{ number_format($penjualan->total_harga) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- Tanda Tangan --}}
        <div style="display: flex; justify-content: space-between; margin-top: 40px; font-size: 13px;">
            <div style="text-align: center; width: 200px;">
                <p style="margin: 0;">Dibuat Oleh,</p>
                <div style="height: 60px;"></div>
                <p style="margin: 0; border-top: 1px solid #333; padding-top: 5px;">(____________________)</p>
            </div>
            <div style="text-align: center; width: 200px;">
                <p style="margin: 0;">Disetujui Oleh,</p>
                <div style="height: 60px;"></div>
                <p style="margin: 0; border-top: 1px solid #333; padding-top: 5px;">(____________________)</p>
            </div>
            <div style="text-align: center; width: 200px;">
                <p style="margin: 0;">Pembeli,</p>
                <div style="height: 60px;"></div>
                <p style="margin: 0; border-top: 1px solid #333; padding-top: 5px;">( {{ $penjualan->nama_pembeli ?? '________________' }} )</p>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    function cetakFaktur() {
        document.getElementById('faktur-print').style.display = 'block';
        window.print();
        setTimeout(function() {
            document.getElementById('faktur-print').style.display = 'none';
        }, 1000);
    }
</script>
@endpush

@endsection