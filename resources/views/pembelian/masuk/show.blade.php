@extends('layouts.app')
@section('title', 'Detail Pembelian')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pembelian.masuk.index') }}" class="text-decoration-none">Barang Masuk</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@push('styles')
<style>
    @page {
            margin: 0;
            size: A4;
        }
    @media print {
        /* Sembunyikan semua elemen kecuali faktur */
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
            padding: 20px;
        }
        .no-print {
            display: none !important;
        }
        .table {
            font-size: 12px;
        }
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-file-text me-2"></i>Detail Pembelian: {{ $pembelian->no_pembelian }}</span>
                <div class="d-flex gap-2">
                    {{-- Tombol Cetak Faktur --}}
                    <button onclick="cetakFaktur()" class="btn btn-sm btn-success no-print">
                        <i class="bi bi-printer me-1"></i>Cetak Faktur
                    </button>
                    <a href="{{ route('pembelian.masuk.index') }}" class="btn btn-sm btn-outline-secondary no-print">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                {{-- Info Umum --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><td class="fw-semibold" width="140">No. Pembelian</td><td>: <span class="badge bg-primary">{{ $pembelian->no_pembelian }}</span></td></tr>
                            <tr><td class="fw-semibold">Supplier</td><td>: {{ $pembelian->supplier->nama_supplier ?? '-' }}</td></tr>
                            <tr><td class="fw-semibold">Tanggal</td><td>: {{ $pembelian->tanggal_pembelian->format('d/m/Y') }}</td></tr>
                            <tr><td class="fw-semibold">Keterangan</td><td>: {{ $pembelian->keterangan ?? '-' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h5 class="fw-bold text-primary">Total: Rp {{ number_format($pembelian->total_harga) }}</h5>
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
                                <th>Qty Masuk</th>
                                <th>Sisa Stok</th>
                                <th>Harga Beli</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pembelian->detail as $i => $d)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="fw-semibold">{{ $d->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $d->barang->satuan->nama_satuan ?? '-' }}</td>
                                <td>{{ $d->qty_masuk }}</td>
                                <td>
                                    @if($d->sisa_qty == 0)
                                        <span class="badge bg-danger">Habis</span>
                                    @elseif($d->sisa_qty < $d->qty_masuk)
                                        <span class="badge bg-warning text-dark">{{ $d->sisa_qty }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $d->sisa_qty }}</span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($d->harga_beli) }}</td>
                                <td>Rp {{ number_format($d->subtotal) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" class="text-end fw-bold">Total</td>
                                <td class="fw-bold">Rp {{ number_format($pembelian->total_harga) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== AREA FAKTUR PRINT (Tersembunyi di layar, tampil saat print) ===== --}}
<div id="faktur-print" style="display:none;">
    <div style="font-family: Arial, sans-serif; max-width: 800px; margin: auto; padding: 40px 50px;">

        {{-- Header Faktur --}}
        <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 20px;">
            <div>
                {{-- Ganti dengan nama & logo perusahaan Anda --}}
                <h2 style="margin:0; font-size: 22px;">Berkah Sedati</h2>
                <p style="margin:4px 0; font-size: 12px; color: #555;">Alamat Perusahaan, Kota, Kode Pos</p>
                <p style="margin:4px 0; font-size: 12px; color: #555;">Telp: (021) 0000-0000 | Email: info@perusahaan.com</p>
            </div>
            <div style="text-align: right;">
                <h3 style="margin:0; font-size: 20px; color: #333;">FAKTUR PEMBELIAN</h3>
                <p style="margin:4px 0; font-size: 13px;"><strong>No:</strong> {{ $pembelian->no_pembelian }}</p>
                <p style="margin:4px 0; font-size: 13px;"><strong>Tanggal:</strong> {{ $pembelian->tanggal_pembelian->format('d/m/Y') }}</p>
            </div>
        </div>

        {{-- Info Supplier --}}
        <div style="margin-bottom: 20px;">
            <table style="font-size: 13px; width: 50%;">
                <tr>
                    <td style="padding: 3px 0; width: 120px;"><strong>Supplier</strong></td>
                    <td>: {{ $pembelian->supplier->nama_supplier ?? '-' }}</td>
                </tr>
                @if(!empty($pembelian->supplier->alamat))
                <tr>
                    <td style="padding: 3px 0;"><strong>Alamat</strong></td>
                    <td>: {{ $pembelian->supplier->alamat }}</td>
                </tr>
                @endif
                @if(!empty($pembelian->supplier->telepon))
                <tr>
                    <td style="padding: 3px 0;"><strong>Telepon</strong></td>
                    <td>: {{ $pembelian->supplier->telepon }}</td>
                </tr>
                @endif
                @if(!empty($pembelian->keterangan))
                <tr>
                    <td style="padding: 3px 0;"><strong>Keterangan</strong></td>
                    <td>: {{ $pembelian->keterangan }}</td>
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
                    <th style="border: 1px solid #ccc; padding: 8px; text-align: right;">Harga Beli</th>
                    <th style="border: 1px solid #ccc; padding: 8px; text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pembelian->detail as $i => $d)
                <tr>
                    <td style="border: 1px solid #ccc; padding: 7px; text-align: center;">{{ $i + 1 }}</td>
                    <td style="border: 1px solid #ccc; padding: 7px;">{{ $d->barang->nama_barang ?? '-' }}</td>
                    <td style="border: 1px solid #ccc; padding: 7px; text-align: center;">{{ $d->barang->satuan->nama_satuan ?? '-' }}</td>
                    <td style="border: 1px solid #ccc; padding: 7px; text-align: center;">{{ $d->qty_masuk }}</td>
                    <td style="border: 1px solid #ccc; padding: 7px; text-align: right;">Rp {{ number_format($d->harga_beli) }}</td>
                    <td style="border: 1px solid #ccc; padding: 7px; text-align: right;">Rp {{ number_format($d->subtotal) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" style="border: 1px solid #ccc; padding: 8px; text-align: right; font-weight: bold;">TOTAL</td>
                    <td style="border: 1px solid #ccc; padding: 8px; text-align: right; font-weight: bold;">Rp {{ number_format($pembelian->total_harga) }}</td>
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
                <p style="margin: 0;">Diterima Oleh,</p>
                <div style="height: 60px;"></div>
                <p style="margin: 0; border-top: 1px solid #333; padding-top: 5px;">(____________________)</p>
            </div>
        </div>


    </div>
</div>

@push('scripts')
<script>
    function cetakFaktur() {
        // Tampilkan area faktur
        document.getElementById('faktur-print').style.display = 'block';
        // Trigger print browser
        window.print();
        // Sembunyikan kembali setelah print dialog ditutup
        setTimeout(function() {
            document.getElementById('faktur-print').style.display = 'none';
        }, 1000);
    }
</script>
@endpush

@endsection