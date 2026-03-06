@extends('layouts.app')
@section('title', 'Data Barang')
@section('breadcrumb')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">Barang</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-box2 me-2"></i>Data Barang</span>
        <div class="d-flex gap-2">
            <a href="{{ route('master.barang.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>Tambah Barang
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="input-group" style="max-width:320px">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Cari kode/nama barang..." value="{{ request('search') }}">
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Gudang</th>
                        <th>Stok</th>
                        <th>Min Stok</th>
                        <th>Harga Jual</th>
                       
                        <th width="160">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barang as $i => $b)
                    @php
                        $activeBatches = $b->batches->where('stok', '>', 0);
                        $hasExpired    = $activeBatches->contains(fn($x) => $x->status_expired === 'expired');
                        $hasWarning    = $activeBatches->contains(fn($x) => $x->status_expired === 'warning');
                    @endphp
                    <tr>
                        <td>{{ $barang->firstItem() + $i }}</td>
                        <td><span class="badge bg-secondary">{{ $b->kode_barang }}</span></td>
                        <td class="fw-semibold">{{ $b->nama_barang }}</td>
                        <td>{{ $b->satuan->nama_satuan ?? '-' }}</td>
                        <td>{{ $b->gudang->nama_gudang ?? '-' }}</td>
                        <td>
                            @if($b->stok_total <= $b->stok_minimum && $b->stok_minimum > 0)
                                <span class="badge bg-danger">{{ $b->stok_total }}</span>
                            @else
                                <span class="badge bg-success">{{ $b->stok_total }}</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $b->stok_minimum }}</td>
                        <td>Rp {{ number_format($b->harga_jual) }}</td>

                        {{-- Kolom Expired --}}
                       
                        </td>

                        <td>
                            {{-- Tombol Show --}}
                            <a href="{{ route('master.barang.show', $b) }}"
                               class="btn btn-sm btn-info text-white" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                           
                            <a href="{{ route('master.barang.edit', $b) }}"
                               class="btn btn-sm btn-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('master.barang.destroy', $b) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Hapus barang {{ $b->nama_barang }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">Belum ada data barang</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $barang->links() }}
    </div>
</div>
@endsection