@extends('layouts.app')
@section('title', 'Barang Masuk')
@section('breadcrumb')
    <li class="breadcrumb-item">Pembelian</li>
    <li class="breadcrumb-item active">Barang Masuk</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-box-arrow-in-down me-2"></i>Data Barang Masuk</span>
        <a href="{{ route('pembelian.masuk.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Tambah Pembelian
        </a>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Cari no. pembelian / supplier..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('pembelian.masuk.index') }}" class="btn btn-sm btn-outline-danger">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>No. Pembelian</th>
                        <th>Supplier</th>
                        <th>Tanggal</th>
                        <th>Total Harga</th>
                        <th>Expired</th>
                        <th>Keterangan</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembelian as $i => $p)
                    @php
                        $expData = $expiredStatus[$p->id] ?? ['status' => 'no_batch', 'tanggal' => null];
                        if (!is_array($expData)) {
                            $expData = ['status' => $expData, 'tanggal' => null];
                        }
                        $tgl = !empty($expData['tanggal']) ? \Carbon\Carbon::parse($expData['tanggal'])->format('d/m/Y') : null;
                    @endphp
                    <tr>
                        <td>{{ $pembelian->firstItem() + $i }}</td>
                        <td><span class="badge bg-primary">{{ $p->no_pembelian }}</span></td>
                        <td class="fw-semibold">{{ $p->supplier->nama_supplier ?? '-' }}</td>
                        <td>{{ $p->tanggal_pembelian->format('d/m/Y') }}</td>
                        <td>Rp {{ number_format($p->total_harga) }}</td>
                        <td>
                            @if($expData['status'] === 'expired')
                                <span class="badge bg-danger">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>Expired
                                </span>
                                @if($tgl)<div class="small text-danger">{{ $tgl }}</div>@endif
                            @elseif($expData['status'] === 'warning')
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-clock me-1"></i>Segera Expired
                                </span>
                                @if($tgl)<div class="small text-warning fw-semibold">{{ $tgl }}</div>@endif
                            @elseif($expData['status'] === 'aman')
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Aman
                                </span>
                                @if($tgl)<div class="small text-muted">{{ $tgl }}</div>@endif
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>{{ $p->keterangan ?? '-' }}</td>
                        <td>
                            <a href="{{ route('pembelian.masuk.show', $p) }}"
                               class="btn btn-sm btn-info text-white" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('pembelian.masuk.edit', $p) }}"
                               class="btn btn-sm btn-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('pembelian.masuk.destroy', $p) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Hapus pembelian {{ $p->no_pembelian }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data pembelian</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $pembelian->links() }}
    </div>
</div>
@endsection