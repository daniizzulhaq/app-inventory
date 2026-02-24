<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok</title>
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
        .status-habis { color: #dc3545; font-weight: bold; }
        .status-menipis { color: #856404; font-weight: bold; }
        .status-aman { color: #198754; font-weight: bold; }
        .footer { margin-top: 16px; font-size: 9px; color: #888; text-align: right; }
    </style>
</head>
<body>
    <h2>Laporan Stok Barang</h2>
    <p class="subtitle">Dicetak pada: {{ date('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="28">No</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>Gudang</th>
                <th class="text-right">Stok</th>
                <th class="text-right">Min</th>
                <th class="text-right">Harga Jual</th>
                <th class="text-right">Nilai Stok</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barang as $i => $b)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $b->kode_barang }}</td>
                <td>{{ $b->nama_barang }}</td>
                <td>{{ $b->satuan->nama_satuan ?? '-' }}</td>
                <td>{{ $b->gudang->nama_gudang ?? '-' }}</td>
                <td class="text-right">{{ $b->stok_total }}</td>
                <td class="text-right">{{ $b->stok_minimum }}</td>
                <td class="text-right">Rp {{ number_format($b->harga_jual) }}</td>
                <td class="text-right">Rp {{ number_format($b->stok_total * $b->harga_jual) }}</td>
                <td class="text-center">
                    @if($b->stok_total == 0)
                        <span class="status-habis">Habis</span>
                    @elseif($b->stok_total <= $b->stok_minimum && $b->stok_minimum > 0)
                        <span class="status-menipis">Menipis</span>
                    @else
                        <span class="status-aman">Aman</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="10" class="text-center">Tidak ada data</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8" class="text-right">Total Nilai Stok</td>
                <td class="text-right">Rp {{ number_format($barang->sum(fn($b) => $b->stok_total * $b->harga_jual)) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">Dicetak: {{ date('d/m/Y H:i:s') }}</div>
</body>
</html>