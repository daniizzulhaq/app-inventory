@extends('layouts.app')
@section('title', 'Tambah Barang')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('master.barang.index') }}" class="text-decoration-none">Barang</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Tambah Barang Baru</div>
            <div class="card-body">
                <form action="{{ route('master.barang.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Barang <span class="text-danger">*</span></label>
                            <input type="text" name="kode_barang" class="form-control @error('kode_barang') is-invalid @enderror"
                                   value="{{ old('kode_barang') }}" placeholder="Cth: BRG-001" required>
                            @error('kode_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror"
                                   value="{{ old('nama_barang') }}" placeholder="Nama barang..." required>
                            @error('nama_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                            <select name="satuan_id" class="form-select @error('satuan_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Satuan --</option>
                                @foreach($satuan as $s)
                                <option value="{{ $s->id }}" {{ old('satuan_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama_satuan }} ({{ $s->singkatan }})
                                </option>
                                @endforeach
                            </select>
                            @error('satuan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Gudang <span class="text-danger">*</span></label>
                            <select name="gudang_id" class="form-select @error('gudang_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Gudang --</option>
                                @foreach($gudang as $g)
                                <option value="{{ $g->id }}" {{ old('gudang_id') == $g->id ? 'selected' : '' }}>{{ $g->nama_gudang }}</option>
                                @endforeach
                            </select>
                            @error('gudang_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga Jual <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror"
                                       value="{{ old('harga_jual', 0) }}" min="0" required>
                            </div>
                            @error('harga_jual')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Stok Minimum</label>
                            <input type="number" name="stok_minimum" class="form-control"
                                   value="{{ old('stok_minimum', 0) }}" min="0">
                            <small class="text-muted">Notifikasi jika stok di bawah angka ini</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan (opsional)">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                        <a href="{{ route('master.barang.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
