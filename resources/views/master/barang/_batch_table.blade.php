<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>No Batch</th>
                <th>Tgl Masuk</th>
                <th>Expired</th>
                <th>Sisa Hari</th>
                <th>Stok</th>
                <th>Harga Jual</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($batches as $batch)
            <tr class="{{ $rowClass }}">
                <td><span class="badge bg-secondary">{{ $batch->no_batch ?? '-' }}</span></td>
                <td>{{ $batch->tanggal_masuk->format('d/m/Y') }}</td>
                <td>{{ $batch->expired_date?->format('d/m/Y') ?? '<span class="text-muted">—</span>' }}</td>
                <td>
                    @if($batch->hari_sisa === null)
                        <span class="text-muted">—</span>
                    @elseif($batch->hari_sisa < 0)
                        <span class="text-danger fw-bold">Lewat {{ abs($batch->hari_sisa) }} hari</span>
                    @elseif($batch->hari_sisa <= 30)
                        <span class="text-warning fw-bold">{{ $batch->hari_sisa }} hari</span>
                    @else
                        {{ $batch->hari_sisa }} hari
                    @endif
                </td>
                <td>
                    @if($batch->stok > 0)
                        <span class="badge bg-success">{{ $batch->stok }}</span>
                    @else
                        <span class="badge bg-secondary">Habis</span>
                    @endif
                </td>
                <td>Rp {{ number_format($batch->harga_beli) }}</td>
                <td class="text-muted small">{{ $batch->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>