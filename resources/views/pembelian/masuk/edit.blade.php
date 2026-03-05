@extends('layouts.app')
@section('title', 'Edit Pembelian')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pembelian.masuk.index') }}">Barang Masuk</a></li>
    <li class="breadcrumb-item"><a href="{{ route('pembelian.masuk.show', $pembelian) }}">{{ $pembelian->no_pembelian }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-pencil me-2"></i>Edit Pembelian: {{ $pembelian->no_pembelian }}</div>
    <div class="card-body">
        <form action="{{ route('pembelian.masuk.update', $pembelian) }}" method="POST" id="formEdit">
            @csrf @method('PUT')
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">No. Pembelian</label>
                    <input type="text" class="form-control bg-light" value="{{ $pembelian->no_pembelian }}" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                    <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($supplier as $s)
                        <option value="{{ $s->id }}" {{ $pembelian->supplier_id == $s->id ? 'selected' : '' }}>
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
                           value="{{ $pembelian->tanggal_pembelian->format('Y-m-d') }}" required>
                    @error('tanggal_pembelian')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control"
                           value="{{ old('keterangan', $pembelian->keterangan) }}"
                           placeholder="Keterangan (opsional)">
                </div>
            </div>

            <h6 class="fw-semibold mb-2"><i class="bi bi-list-ul me-1"></i>Detail Barang</h6>
            <div class="alert alert-warning py-2 small">
                <i class="bi bi-info-circle me-1"></i>
                Hanya pembelian yang belum ada barang terjual yang bisa diedit.
            </div>
            <div class="table-responsive mb-2">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Barang</th>
                            <th width="90">Qty</th>
                            <th width="150">Harga Beli (Rp)</th>
                            <th width="150">Expired Date</th>
                            <th width="140">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pembelian->detail as $i => $d)
                        <input type="hidden" name="items[{{ $i }}][detail_id]" value="{{ $d->id }}">
                        <tr>
                            <td>
                                {{-- Barang tidak bisa diganti, hanya readonly --}}
                                <input type="hidden" name="items[{{ $i }}][barang_id]" value="{{ $d->barang_id }}">
                                <div class="fw-semibold">{{ $d->barang->nama_barang ?? '-' }}</div>
                                <small class="text-muted">{{ $d->barang->satuan->nama_satuan ?? '' }}</small>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $i }}][qty]"
                                       class="form-control form-control-sm input-qty"
                                       value="{{ old("items.{$i}.qty", $d->qty_masuk) }}"
                                       min="1" required>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $i }}][harga_beli]"
                                       class="form-control form-control-sm input-harga"
                                       value="{{ old("items.{$i}.harga_beli", $d->harga_beli) }}"
                                       min="0" required>
                            </td>
                            <td>
                                <input type="date" name="items[{{ $i }}][expired_date]"
                                       class="form-control form-control-sm"
                                       value="{{ old("items.{$i}.expired_date", $d->batch?->expired_date?->format('Y-m-d')) }}">
                                <small class="text-muted" style="font-size:10px;">Opsional</small>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm input-subtotal"
                                       value="{{ number_format($d->qty_masuk * $d->harga_beli, 0, ',', '.') }}"
                                       disabled>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-semibold">Total</td>
                            <td>
                                <input type="text" id="grandTotal" class="form-control form-control-sm fw-bold"
                                       value="{{ number_format($pembelian->total_harga, 0, ',', '.') }}" disabled>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <hr>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Update
                </button>
                <a href="{{ route('pembelian.masuk.show', $pembelian) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    function hitungSubtotal(row) {
        const qty   = parseFloat(row.querySelector('.input-qty').value) || 0;
        const harga = parseFloat(row.querySelector('.input-harga').value) || 0;
        row.querySelector('.input-subtotal').value = formatRupiah(qty * harga);
        hitungTotal();
    }

    function hitungTotal() {
        let total = 0;
        document.querySelectorAll('tbody tr').forEach(row => {
            const qty   = parseFloat(row.querySelector('.input-qty')?.value) || 0;
            const harga = parseFloat(row.querySelector('.input-harga')?.value) || 0;
            total += qty * harga;
        });
        document.getElementById('grandTotal').value = formatRupiah(total);
    }

    document.querySelectorAll('tbody tr').forEach(row => {
        row.querySelector('.input-qty')?.addEventListener('input', () => hitungSubtotal(row));
        row.querySelector('.input-harga')?.addEventListener('input', () => hitungSubtotal(row));
    });
</script>
@endpush
@endsection