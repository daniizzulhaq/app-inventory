@extends('layouts.app')
@section('title', 'Tambah Mutasi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pembelian.mutasi.index') }}" class="text-decoration-none">Mutasi</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Tambah Mutasi Barang</div>
            <div class="card-body">
                <form action="{{ route('pembelian.mutasi.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. Mutasi</label>
                            <input type="text" class="form-control" value="{{ $noMutasi }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mutasi"
                                   class="form-control @error('tanggal_mutasi') is-invalid @enderror"
                                   value="{{ old('tanggal_mutasi', date('Y-m-d')) }}" required>
                            @error('tanggal_mutasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Barang <span class="text-danger">*</span></label>
                            <select name="barang_id" class="form-select @error('barang_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barang as $b)
                                <option value="{{ $b->id }}" {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->nama_barang }} — Stok: {{ $b->stok_total }} {{ $b->satuan->nama_satuan ?? '' }}
                                </option>
                                @endforeach
                            </select>
                            @error('barang_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Gudang Asal <span class="text-danger">*</span></label>
                            <select name="gudang_asal_id" class="form-select @error('gudang_asal_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Gudang --</option>
                                @foreach($gudang as $g)
                                <option value="{{ $g->id }}" {{ old('gudang_asal_id') == $g->id ? 'selected' : '' }}>{{ $g->nama_gudang }}</option>
                                @endforeach
                            </select>
                            @error('gudang_asal_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Gudang Tujuan <span class="text-danger">*</span></label>
                            <select name="gudang_tujuan_id" class="form-select @error('gudang_tujuan_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Gudang --</option>
                                @foreach($gudang as $g)
                                <option value="{{ $g->id }}" {{ old('gudang_tujuan_id') == $g->id ? 'selected' : '' }}>{{ $g->nama_gudang }}</option>
                                @endforeach
                            </select>
                            @error('gudang_tujuan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Qty <span class="text-danger">*</span></label>
                            <input type="number" name="qty"
                                   class="form-control @error('qty') is-invalid @enderror"
                                   value="{{ old('qty', 1) }}" min="1" required>
                            @error('qty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2"
                                      placeholder="Keterangan (opsional)">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                        <a href="{{ route('pembelian.mutasi.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection