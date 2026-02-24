<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Barang Masuk</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; margin: 20px; }
        h2 { text-align: center; margin-bottom: 4px; font-size: 15px; }
        p.subtitle { text-align: center; margin: 0 0 14px; font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #e9ecef; border: 1px solid #aaa; padding: 5px 6px; text-align: left; font-size: 10px; }
        td { border: 1px solid #ccc; padding: 5px 6px; font-size: 10px; }
        tr:nth-child(even) td { background-color: #f8f9fa; }
        tfoot td { font-weight: bold; background-color: #e9ecef; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 16px; font-size: 9px; color: #888; text-align: right; }
    </style>
</head>
<body>
    <h2>Laporan Barang Masuk</h2>
    <p class="subtitle">
        Periode: {{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}
    </p>
    <table>
        <thead>
            <tr>
                <th class="text-center" width="28">No</th>
                <th>Tanggal</th>
                <th>No. Pembelian</th>
                <th>Supplier</th>
                <th>Barang</th>
                <th>Satuan</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Harga Beli</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $d)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $d->tanggal_masuk->format('d/m/Y') }}</td>
                <td>{{ $d->pembelian->no_pembelian ?? '-' }}</td>
                <td>{{ $d->pembelian->supplier->nama_supplier ?? '-' }}</td>
                <td>{{ $d->barang->nama_barang ?? '-' }}</td>
                <td>{{ $d->barang->satuan->nama_satuan ?? '-' }}</td>
                <td class="text-right">{{ $d->qty_masuk }}</td>
                <td class="text-right">Rp {{ number_format($d->harga_beli) }}</td>
                <td class="text-right">Rp {{ number_format($d->subtotal) }}</td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center">Tidak ada data pada periode ini</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8" class="text-right">Total</td>
                <td class="text-right">Rp {{ number_format($total) }}</td>
            </tr>
        </tfoot>
    </table>
    <div class="footer">Dicetak: {{ date('d/m/Y H:i:s') }}</div>
</body>
</html>