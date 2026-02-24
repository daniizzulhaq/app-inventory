@extends('layouts.app')
@section('title', 'History Pembelian')
@section('breadcrumb')
    <li class="breadcrumb-item">Pembelian</li>
    <li class="breadcrumb-item active">History</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-clock-history me-2"></i>History Barang Masuk
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <select name="barang_id" class="form-select form-select-sm">
                    <option value="">-- Semua Barang --</option>
                    @foreach($barang as $b)
                    <option value="{{ $b->id }}" {{ request('barang_id') == $b->id ? 'selected' : '' }}>
                        {{ $b->nama_barang }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('pembelian.history') }}" class="btn btn-sm btn-outline-danger">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>No. Pembelian</th>
                        <th>Supplier</th>
                        <th>Barang</th>
                        <th>Satuan</th>
                        <th>Qty Masuk</th>
                        <th>Sisa</th>
                        <th>Harga Beli</th>
                        <th>Subtotal</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $i => $h)
                    <tr>
                        <td>{{ $history->firstItem() + $i }}</td>
                        <td><span class="badge bg-primary">{{ $h->pembelian->no_pembelian ?? '-' }}</span></td>
                        <td>{{ $h->pembelian->supplier->nama_supplier ?? '-' }}</td>
                        <td class="fw-semibold">{{ $h->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $h->barang->satuan->nama_satuan ?? '-' }}</td>
                        <td>{{ $h->qty_masuk }}</td>
                        <td>
                            @if($h->sisa_qty == 0)
                                <span class="badge bg-danger">Habis</span>
                            @elseif($h->sisa_qty < $h->qty_masuk)
                                <span class="badge bg-warning text-dark">{{ $h->sisa_qty }}</span>
                            @else
                                <span class="badge bg-success">{{ $h->sisa_qty }}</span>
                            @endif
                        </td>
                        <td>Rp {{ number_format($h->harga_beli) }}</td>
                        <td>Rp {{ number_format($h->subtotal) }}</td>
                        <td>{{ $h->tanggal_masuk->format('d/m/Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center py-4 text-muted">Belum ada history pembelian</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $history->links() }}
    </div>
</div>
@endsection