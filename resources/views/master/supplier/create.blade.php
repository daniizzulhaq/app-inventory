@extends('layouts.app')
@section('title', 'Tambah Supplier')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('supplier.index') }}" class="text-decoration-none">Supplier</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Tambah Supplier Baru</div>
            <div class="card-body">
                <form action="{{ route('supplier.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Supplier <span class="text-danger">*</span></label>
                            <input type="text" name="kode_supplier"
                                   class="form-control @error('kode_supplier') is-invalid @enderror"
                                   value="{{ old('kode_supplier') }}" placeholder="Cth: SUP-001" required>
                            @error('kode_supplier')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Supplier <span class="text-danger">*</span></label>
                            <input type="text" name="nama_supplier"
                                   class="form-control @error('nama_supplier') is-invalid @enderror"
                                   value="{{ old('nama_supplier') }}" placeholder="Nama supplier..." required>
                            @error('nama_supplier')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Telepon</label>
                            <input type="text" name="telepon" class="form-control"
                                   value="{{ old('telepon') }}" placeholder="Nomor telepon...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" placeholder="email@contoh.com">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2"
                                      placeholder="Alamat (opsional)">{{ old('alamat') }}</textarea>
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
                        <a href="{{ route('supplier.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection