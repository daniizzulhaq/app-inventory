@extends('layouts.app')
@section('title', 'Batch — ' . $barang->nama_barang)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('master.barang.index') }}">Barang</a></li>
    <li class="breadcrumb-item active">Batch</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-layers me-2"></i>Batch: <strong>{{ $barang->nama_barang }}</strong></span>
        <a href="{{ route('master.barang.batch.create', $barang) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Tambah Batch
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No Batch</th>
                        <th>Tgl Masuk</th>
                        <th>Expired</th>
                        <th>Sisa Hari</th>
                        <th>Stok</th>
                        <th>Harga Beli</th>
                        <th>Status</th>
                        <th width="110">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batches as $b)
                    @php
                        $status = $b->status_expired;
                        $rowClass = match($status) {
                            'expired' => 'table-danger',
                            'warning' => 'table-warning',
                            default   => '',
                        };
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td><span class="badge bg-secondary">{{ $b->no_batch ?? '-' }}</span></td>
                        <td>{{ $b->tanggal_masuk->format('d/m/Y') }}</td>
                        <td>{{ $b->expired_date?->format('d/m/Y') ?? '<span class="text-muted">—</span>' }}</td>
                        <td>
                            @if($b->hari_sisa === null)
                                <span class="text-muted">—</span>
                            @elseif($b->hari_sisa < 0)
                                <span class="text-danger fw-bold">Lewat {{ abs($b->hari_sisa) }} hari</span>
                            @else
                                {{ $b->hari_sisa }} hari
                            @endif
                        </td>
                        <td>{{ number_format($b->stok) }}</td>
                        <td>Rp {{ number_format($b->harga_beli) }}</td>
                        <td>
                            @if($status === 'expired')
                                <span class="badge bg-danger">Expired</span>
                            @elseif($status === 'warning')
                                <span class="badge bg-warning text-dark">Segera Expired</span>
                            @elseif($status === 'no_expired')
                                <span class="badge bg-secondary">Tanpa Exp.</span>
                            @else
                                <span class="badge bg-success">Aman</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('master.barang.batch.edit', [$barang, $b]) }}"
                               class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('master.barang.batch.destroy', [$barang, $b]) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus batch ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada batch</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $batches->links() }}
    </div>
</div>
@endsection