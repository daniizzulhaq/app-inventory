<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Laba Rugi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; margin: 20px; }
        h2 { text-align: center; margin-bottom: 4px; font-size: 15px; }
        p.subtitle { text-align: center; margin: 0 0 14px; font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { background-color: #e9ecef; border: 1px solid #aaa; padding: 5px 6px; text-align: left; font-size: 10px; }
        td { border: 1px solid #ccc; padding: 5px 6px; font-size: 10px; }
        tr:nth-child(even) td { background-color: #f8f9fa; }
        tfoot td { font-weight: bold; background-color: #e9ecef; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .laba { color: #198754; font-weight: bold; }
        .rugi { color: #dc3545; font-weight: bold; }
        .label-cell { background-color: #e9ecef; font-weight: bold; width: 35%; }
        h3 { font-size: 12px; margin: 16px 0 6px; border-bottom: 1px solid #ccc; padding-bottom: 4px; }
        .footer { margin-top: 16px; font-size: 9px; color: #888; text-align: right; }
    </style>
</head>
<body>
    <h2>Laporan Laba Rugi</h2>
    <p class="subtitle">
        Periode: {{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}
    </p>

    <h3>Ringkasan</h3>
    <table style="width:50%">
        <tr>
            <td class="label-cell">Total Penjualan</td>
            <td class="text-right">Rp {{ number_format($totalPenjualan) }}</td>
        </tr>
        <tr>
            <td class="label-cell">Total HPP</td>
            <td class="text-right">Rp {{ number_format($totalHpp) }}</td>
        </tr>
        <tr>
            <td class="label-cell">{{ $totalLaba >= 0 ? 'Total Laba' : 'Total Rugi' }}</td>
            <td class="text-right {{ $totalLaba >= 0 ? 'laba' : 'rugi' }}">
                Rp {{ number_format($totalLaba) }}
            </td>
        </tr>
    </table>

    <h3>Rincian Per Barang</h3>
    <table>
        <thead>
            <tr>
                <th class="text-center" width="28">No</th>
                <th>Nama Barang</th>
                <th class="text-right">Qty Terjual</th>
                <th class="text-right">Total Penjualan</th>
                <th class="text-right">Total HPP</th>
                <th class="text-right">Laba</th>
                <th class="text-right">Margin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($perBarang as $i => $b)
            @php $margin = $b->total_penjualan > 0 ? ($b->total_laba / $b->total_penjualan * 100) : 0; @endphp
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $b->nama_barang }}</td>
                <td class="text-right">{{ $b->total_qty }}</td>
                <td class="text-right">Rp {{ number_format($b->total_penjualan) }}</td>
                <td class="text-right">Rp {{ number_format($b->total_hpp) }}</td>
                <td class="text-right {{ $b->total_laba >= 0 ? 'laba' : 'rugi' }}">
                    Rp {{ number_format($b->total_laba) }}
                </td>
                <td class="text-right {{ $margin >= 0 ? 'laba' : 'rugi' }}">
                    {{ number_format($margin, 1) }}%
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center">Tidak ada data pada periode ini</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Dicetak: {{ date('d/m/Y H:i:s') }}</div>
</body>
</html>