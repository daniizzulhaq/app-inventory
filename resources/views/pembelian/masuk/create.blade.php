@extends('layouts.app')
@section('title', 'Tambah Pembelian')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pembelian.masuk.index') }}" class="text-decoration-none">Barang Masuk</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Tambah Pembelian Baru</div>
    <div class="card-body">
        <form action="{{ route('pembelian.masuk.store') }}" method="POST" id="formPembelian">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">No. Pembelian</label>
                    <input type="text" class="form-control" value="{{ $noPembelian }}" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                    <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($supplier as $s)
                        <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->nama_supplier }}
                        </option>
                        @endforeach
                    </select>
                    @error('supplier_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_pembelian"
                           class="form-control @error('tanggal_pembelian') is-invalid @enderror"
                           value="{{ old('tanggal_pembelian', date('Y-m-d')) }}" required>
                    @error('tanggal_pembelian')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control"
                           value="{{ old('keterangan') }}" placeholder="Keterangan (opsional)">
                </div>
            </div>

            <!-- Tabel Item -->
            <h6 class="fw-semibold mb-2"><i class="bi bi-list-ul me-1"></i>Detail Barang</h6>
            <div class="table-responsive mb-2">
                <table class="table table-bordered align-middle" id="tabelItem">
                    <thead class="table-light">
                        <tr>
                            <th>Barang</th>
                            <th width="120">Qty</th>
                            <th width="160">Harga Beli (Rp)</th>
                            <th width="160">Subtotal</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody id="itemBody">
                        <tr id="row-0">
                            <td>
                                <select name="items[0][barang_id]" class="form-select form-select-sm select-barang" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($barang as $b)
                                    <option value="{{ $b->id }}">{{ $b->nama_barang }} ({{ $b->satuan->nama_satuan ?? '' }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="items[0][qty]" class="form-control form-control-sm input-qty" min="1" value="1" required></td>
                            <td><input type="number" name="items[0][harga_beli]" class="form-control form-control-sm input-harga" min="0" value="0" required></td>
                            <td><input type="text" class="form-control form-control-sm input-subtotal" value="0" disabled></td>
                            <td><button type="button" class="btn btn-sm btn-danger btn-hapus-row"><i class="bi bi-trash"></i></button></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-semibold">Total</td>
                            <td><input type="text" id="grandTotal" class="form-control form-control-sm fw-bold" value="0" disabled></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="btnTambahRow">
                <i class="bi bi-plus-circle me-1"></i>Tambah Barang
            </button>

            <hr>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('pembelian.masuk.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const barangOptions = `@foreach($barang as $b)<option value="{{ $b->id }}">{{ $b->nama_barang }} ({{ $b->satuan->nama_satuan ?? '' }})</option>@endforeach`;
    let rowIndex = 1;

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    function hitungSubtotal(row) {
        const qty = parseFloat(row.querySelector('.input-qty').value) || 0;
        const harga = parseFloat(row.querySelector('.input-harga').value) || 0;
        const subtotal = qty * harga;
        row.querySelector('.input-subtotal').value = formatRupiah(subtotal);
        hitungTotal();
    }

    function hitungTotal() {
        let total = 0;
        document.querySelectorAll('#itemBody tr').forEach(row => {
            const qty = parseFloat(row.querySelector('.input-qty').value) || 0;
            const harga = parseFloat(row.querySelector('.input-harga').value) || 0;
            total += qty * harga;
        });
        document.getElementById('grandTotal').value = formatRupiah(total);
    }

    document.getElementById('btnTambahRow').addEventListener('click', function () {
        const tbody = document.getElementById('itemBody');
        const tr = document.createElement('tr');
        tr.id = 'row-' + rowIndex;
        tr.innerHTML = `
            <td><select name="items[${rowIndex}][barang_id]" class="form-select form-select-sm select-barang" required>
                <option value="">-- Pilih Barang --</option>${barangOptions}</select></td>
            <td><input type="number" name="items[${rowIndex}][qty]" class="form-control form-control-sm input-qty" min="1" value="1" required></td>
            <td><input type="number" name="items[${rowIndex}][harga_beli]" class="form-control form-control-sm input-harga" min="0" value="0" required></td>
            <td><input type="text" class="form-control form-control-sm input-subtotal" value="0" disabled></td>
            <td><button type="button" class="btn btn-sm btn-danger btn-hapus-row"><i class="bi bi-trash"></i></button></td>`;
        tbody.appendChild(tr);
        bindRowEvents(tr);
        rowIndex++;
    });

    function bindRowEvents(row) {
        row.querySelector('.input-qty').addEventListener('input', () => hitungSubtotal(row));
        row.querySelector('.input-harga').addEventListener('input', () => hitungSubtotal(row));
        row.querySelector('.btn-hapus-row').addEventListener('click', function () {
            if (document.querySelectorAll('#itemBody tr').length > 1) {
                row.remove();
                hitungTotal();
            }
        });
    }

    // Bind row pertama
    const firstRow = document.querySelector('#itemBody tr');
    bindRowEvents(firstRow);
</script>
@endpush
@endsection