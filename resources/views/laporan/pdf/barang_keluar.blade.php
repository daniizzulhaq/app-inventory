<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Barang Keluar</title>
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
        .laba { color: #198754; font-weight: bold; }
        .rugi { color: #dc3545; font-weight: bold; }
        .footer { margin-top: 16px; font-size: 9px; color: #888; text-align: right; }
    </style>
</head>
<body>
    <h2>Laporan Barang Keluar</h2>
    <p class="subtitle">
        Periode: {{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}
    </p>
    <table>
        <thead>
            <tr>
                <th class="text-center" width="28">No</th>
                <th>Tanggal</th>
                <th>No. Invoice</th>
                <th>Nama Pembeli</th>
                <th class="text-right">Total Harga</th>
                <th class="text-right">Total HPP</th>
                <th class="text-right">Laba</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $p)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($p->tanggal_penjualan)->format('d/m/Y') }}</td>
                <td>{{ $p->no_invoice }}</td>
                <td>{{ $p->nama_pembeli ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($p->total_harga) }}</td>
                <td class="text-right">Rp {{ number_format($p->total_hpp) }}</td>
                <td class="text-right {{ $p->laba >= 0 ? 'laba' : 'rugi' }}">
                    Rp {{ number_format($p->laba) }}
                </td>
                <td>{{ $p->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center">Tidak ada data pada periode ini</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right">Total</td>
                <td class="text-right">Rp {{ number_format($totalHarga) }}</td>
                <td class="text-right">Rp {{ number_format($totalHpp) }}</td>
                <td class="text-right {{ $totalLaba >= 0 ? 'laba' : 'rugi' }}">
                    Rp {{ number_format($totalLaba) }}
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    <div class="footer">Dicetak: {{ date('d/m/Y H:i:s') }}</div>
</body>
</html>