@extends('layouts.app')
@section('title', 'Tambah Penjualan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('penjualan.keluar.index') }}" class="text-decoration-none">Barang Keluar</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Tambah Penjualan Baru</div>
    <div class="card-body">
        <form action="{{ route('penjualan.keluar.store') }}" method="POST" id="formPenjualan">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">No. Invoice</label>
                    <input type="text" class="form-control" value="{{ $noInvoice }}" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Nama Pembeli</label>
                    <input type="text" name="nama_pembeli" class="form-control"
                           value="{{ old('nama_pembeli') }}" placeholder="Nama pembeli (opsional)">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_penjualan"
                           class="form-control @error('tanggal_penjualan') is-invalid @enderror"
                           value="{{ old('tanggal_penjualan', date('Y-m-d')) }}" required>
                    @error('tanggal_penjualan')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                            <th width="170">Harga Jual (Rp)</th>
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
                                    <option value="{{ $b->id }}" data-harga="{{ $b->harga_jual }}">
                                        {{ $b->nama_barang }} — Stok: {{ $b->stok_total }} {{ $b->satuan->nama_satuan ?? '' }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="items[0][qty]" class="form-control form-control-sm input-qty" min="1" value="1" required></td>
                            <td><input type="number" name="items[0][harga_jual]" class="form-control form-control-sm input-harga" min="0" value="0" required></td>
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
                <a href="{{ route('penjualan.keluar.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const barangOptions = `@foreach($barang as $b)<option value="{{ $b->id }}" data-harga="{{ $b->harga_jual }}">{{ $b->nama_barang }} — Stok: {{ $b->stok_total }} {{ $b->satuan->nama_satuan ?? '' }}</option>@endforeach`;
    let rowIndex = 1;

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    function hitungSubtotal(row) {
        const qty = parseFloat(row.querySelector('.input-qty').value) || 0;
        const harga = parseFloat(row.querySelector('.input-harga').value) || 0;
        row.querySelector('.input-subtotal').value = formatRupiah(qty * harga);
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

    function bindRowEvents(row) {
        const selectBarang = row.querySelector('.select-barang');
        const inputHarga   = row.querySelector('.input-harga');

        selectBarang.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            const harga = opt.dataset.harga || 0;
            inputHarga.value = harga;
            hitungSubtotal(row);
        });

        row.querySelector('.input-qty').addEventListener('input', () => hitungSubtotal(row));
        row.querySelector('.input-harga').addEventListener('input', () => hitungSubtotal(row));
        row.querySelector('.btn-hapus-row').addEventListener('click', function () {
            if (document.querySelectorAll('#itemBody tr').length > 1) {
                row.remove();
                hitungTotal();
            }
        });
    }

    document.getElementById('btnTambahRow').addEventListener('click', function () {
        const tbody = document.getElementById('itemBody');
        const tr = document.createElement('tr');
        tr.id = 'row-' + rowIndex;
        tr.innerHTML = `
            <td><select name="items[${rowIndex}][barang_id]" class="form-select form-select-sm select-barang" required>
                <option value="">-- Pilih Barang --</option>${barangOptions}</select></td>
            <td><input type="number" name="items[${rowIndex}][qty]" class="form-control form-control-sm input-qty" min="1" value="1" required></td>
            <td><input type="number" name="items[${rowIndex}][harga_jual]" class="form-control form-control-sm input-harga" min="0" value="0" required></td>
            <td><input type="text" class="form-control form-control-sm input-subtotal" value="0" disabled></td>
            <td><button type="button" class="btn btn-sm btn-danger btn-hapus-row"><i class="bi bi-trash"></i></button></td>`;
        tbody.appendChild(tr);
        bindRowEvents(tr);
        rowIndex++;
    });

    bindRowEvents(document.querySelector('#itemBody tr'));
</script>
@endpush
@endsection