@extends('layouts.app')
@section('title', 'Edit Batch')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('master.barang.index') }}">Barang</a></li>
    <li class="breadcrumb-item"><a href="{{ route('master.barang.batch.index', $barang) }}">Batch — {{ $barang->nama_barang }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil me-2"></i>Edit Batch — <strong>{{ $barang->nama_barang }}</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('master.barang.batch.update', [$barang, $batch]) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No Batch / Lot</label>
                            <input type="text" name="no_batch" class="form-control"
                                   value="{{ old('no_batch', $batch->no_batch) }}"
                                   placeholder="Cth: LOT-2024-01">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Masuk <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_masuk"
                                   class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                   value="{{ old('tanggal_masuk', $batch->tanggal_masuk->format('Y-m-d')) }}"
                                   required>
                            @error('tanggal_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Expired Date</label>
                            <input type="date" name="expired_date"
                                   class="form-control @error('expired_date') is-invalid @enderror"
                                   value="{{ old('expired_date', $batch->expired_date?->format('Y-m-d')) }}">
                            <small class="text-muted">Kosongkan jika tidak ada expired</small>
                            @error('expired_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stok"
                                   class="form-control @error('stok') is-invalid @enderror"
                                   value="{{ old('stok', $batch->stok) }}"
                                   min="0" required>
                            @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            {{-- Harga jual tampil saja, tidak disubmit — diambil dari barang di controller --}}
                            <label class="form-label fw-semibold">Harga Jual</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control bg-light"
                                       value="{{ number_format($barang->harga_jual, 0, ',', '.') }}"
                                       disabled>
                            </div>
                            <small class="text-muted">Mengikuti harga jual barang</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $batch->keterangan) }}</textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Update
                        </button>
                        <a href="{{ route('master.barang.batch.index', $barang) }}"
                           class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection