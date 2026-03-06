@extends('layouts.app')
@section('title', 'Tambah Penjualan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('penjualan.keluar.index') }}" class="text-decoration-none">Barang Keluar</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@push('styles')
<style>
.batch-selector { display:none; background:#f8f9fa; border:1px solid #dee2e6; border-radius:6px; padding:10px; margin-top:6px; }
.batch-selector.show { display:block; }
.batch-row { display:flex; align-items:center; gap:8px; padding:5px 0; border-bottom:1px solid #eee; }
.batch-row:last-child { border-bottom:none; }
.batch-qty-input { width:80px; }
.badge-expired   { background:#dc3545; color:#fff; }
.badge-warning   { background:#ffc107; color:#000; }
.badge-aman      { background:#198754; color:#fff; }
.badge-noexp     { background:#6c757d; color:#fff; }
</style>
@endpush

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

            <h6 class="fw-semibold mb-2"><i class="bi bi-list-ul me-1"></i>Detail Barang</h6>
            <div class="table-responsive mb-2">
                <table class="table table-bordered align-middle" id="tabelItem">
                    <thead class="table-light">
                        <tr>
                            <th>Barang</th>
                            <th width="110">Qty</th>
                            <th width="160">Harga Jual (Rp)</th>
                            <th>Batch / Expired</th>
                            <th width="150">Subtotal</th>
                            <th width="44"></th>
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
                            <td>
                                <div class="expired-summary text-muted small">— pilih barang dulu</div>
                                <div class="batch-selector">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-semibold small"><i class="bi bi-boxes me-1"></i>Pilih Batch Manual</span>
                                        <button type="button" class="btn btn-xs btn-outline-secondary btn-reset-fifo py-0 px-2" style="font-size:11px;">
                                            <i class="bi bi-arrow-counterclockwise"></i> Reset FIFO
                                        </button>
                                    </div>
                                    <div class="batch-list"></div>
                                    <div class="text-danger small mt-1 batch-warning" style="display:none">
                                        <i class="bi bi-exclamation-triangle"></i> Total qty batch melebihi qty item!
                                    </div>
                                </div>
                                <button type="button" class="btn btn-link btn-sm p-0 mt-1 btn-toggle-batch" style="font-size:12px; display:none;">
                                    <i class="bi bi-sliders me-1"></i>Ubah pilihan batch
                                </button>
                                <!-- hidden inputs batch manual -->
                                <div class="batch-hidden-inputs"></div>
                            </td>
                            <td><input type="text" class="form-control form-control-sm input-subtotal" value="0" disabled></td>
                            <td><button type="button" class="btn btn-sm btn-danger btn-hapus-row"><i class="bi bi-trash"></i></button></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-semibold">Total</td>
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
// Data semua batch per barang dari server
// Format: { barang_id: [ {id, no_batch, sisa_qty, expired_date, status, tanggal_masuk}, ... ] }
const allBatches = @json($batchPerBarang);

const barangOptions = `@foreach($barang as $b)<option value="{{ $b->id }}" data-harga="{{ $b->harga_jual }}">{{ $b->nama_barang }} — Stok: {{ $b->stok_total }} {{ $b->satuan->nama_satuan ?? '' }}</option>@endforeach`;
let rowIndex = 1;

function formatRupiah(n) {
    return new Intl.NumberFormat('id-ID').format(n);
}

function hitungSubtotal(row) {
    const qty   = parseFloat(row.querySelector('.input-qty').value) || 0;
    const harga = parseFloat(row.querySelector('.input-harga').value) || 0;
    row.querySelector('.input-subtotal').value = formatRupiah(qty * harga);
    hitungTotal();
}

function hitungTotal() {
    let total = 0;
    document.querySelectorAll('#itemBody tr').forEach(row => {
        const qty   = parseFloat(row.querySelector('.input-qty').value) || 0;
        const harga = parseFloat(row.querySelector('.input-harga').value) || 0;
        total += qty * harga;
    });
    document.getElementById('grandTotal').value = formatRupiah(total);
}

// Simulasi FIFO: kembalikan array {batch_id, qty} sesuai urutan FIFO
function simulateFifo(barangId, qty) {
    const batches = allBatches[barangId] || [];
    let sisa = parseInt(qty) || 1;
    const result = [];
    for (const b of batches) {
        if (sisa <= 0) break;
        const ambil = Math.min(b.sisa_qty, sisa);
        sisa -= ambil;
        result.push({ batch_id: b.id, qty: ambil, batch: b });
    }
    return result;
}

function badgeClass(status) {
    if (status === 'expired') return 'badge-expired';
    if (status === 'warning') return 'badge-warning';
    if (status === 'aman')    return 'badge-aman';
    return 'badge-noexp';
}
function badgeIcon(status) {
    if (status === 'expired') return '✗';
    if (status === 'warning') return '⚠';
    if (status === 'aman')    return '✓';
    return '—';
}

// Render ringkasan expired di summary (atas)
function renderSummary(fifoResult, summaryEl) {
    if (!fifoResult || !fifoResult.length) {
        summaryEl.innerHTML = `<span class="text-muted">Tidak ada batch tersedia</span>`;
        return;
    }
    let html = '';
    fifoResult.forEach(item => {
        const b   = item.batch;
        const cls = badgeClass(b.status);
        const ico = badgeIcon(b.status);
        const tgl = b.expired_date ?? 'No Exp';
        html += `<span class="badge ${cls} me-1 mb-1" title="Batch: ${b.no_batch}">${ico} ${tgl} <small>(${item.qty})</small></span>`;
    });
    summaryEl.innerHTML = html;
}

// Render daftar batch manual di selector
function renderBatchList(barangId, fifoResult, row) {
    const batches    = allBatches[barangId] || [];
    const batchList  = row.querySelector('.batch-list');
    const hiddenWrap = row.querySelector('.batch-hidden-inputs');
    const warning    = row.querySelector('.batch-warning');

    if (!batches.length) {
        batchList.innerHTML = `<div class="text-muted small">Tidak ada batch tersedia</div>`;
        return;
    }

    // Map fifo default qty per batch_id
    const fifoMap = {};
    (fifoResult || []).forEach(f => { fifoMap[f.batch_id] = f.qty; });

    let html = '';
    batches.forEach((b, i) => {
        const defaultQty = fifoMap[b.id] || 0;
        const cls = badgeClass(b.status);
        const ico = badgeIcon(b.status);
        const tgl = b.expired_date ?? 'No Exp';
        html += `
        <div class="batch-row">
            <div style="flex:1; min-width:0;">
                <span class="badge bg-dark me-1" style="font-size:10px;">${b.no_batch}</span>
                <span class="badge ${cls}" style="font-size:10px;">${ico} ${tgl}</span>
                <span class="text-muted" style="font-size:11px;"> Tgl Masuk: ${b.tanggal_masuk} | Stok: ${b.sisa_qty}</span>
            </div>
            <div>
                <label class="text-muted" style="font-size:11px;">Qty:</label>
                <input type="number" class="form-control form-control-sm batch-qty-input"
                    data-batch-id="${b.id}" data-max="${b.sisa_qty}"
                    min="0" max="${b.sisa_qty}" value="${defaultQty}">
            </div>
        </div>`;
    });
    batchList.innerHTML = html;
    hiddenWrap.innerHTML = '';

    // Bind events pada qty input batch
    batchList.querySelectorAll('.batch-qty-input').forEach(input => {
        input.addEventListener('input', () => validateAndSyncBatch(row, barangId));
    });

    validateAndSyncBatch(row, barangId);
}

// Validasi total qty batch vs qty item, update hidden inputs
function validateAndSyncBatch(row, barangId) {
    const qtyItem    = parseInt(row.querySelector('.input-qty').value) || 0;
    const inputs     = row.querySelectorAll('.batch-qty-input');
    const warning    = row.querySelector('.batch-warning');
    const hiddenWrap = row.querySelector('.batch-hidden-inputs');
    const summaryEl  = row.querySelector('.expired-summary');
    const rowName    = row.querySelector('.select-barang').name.match(/items\[(\d+)\]/)[1];

    let totalBatch = 0;
    const selected = [];

    inputs.forEach(inp => {
        const v = parseInt(inp.value) || 0;
        const max = parseInt(inp.dataset.max);
        if (v > max) { inp.value = max; }
        totalBatch += parseInt(inp.value) || 0;
        if ((parseInt(inp.value) || 0) > 0) {
            selected.push({ batch_id: inp.dataset.batchId, qty: parseInt(inp.value) });
        }
    });

    warning.style.display = totalBatch > qtyItem ? 'block' : 'none';

    // Rebuild hidden inputs untuk submit
    hiddenWrap.innerHTML = '';
    selected.forEach((s, i) => {
        hiddenWrap.innerHTML += `
            <input type="hidden" name="items[${rowName}][batches][${i}][batch_id]" value="${s.batch_id}">
            <input type="hidden" name="items[${rowName}][batches][${i}][qty]" value="${s.qty}">`;
    });

    // Update summary badge berdasarkan pilihan manual
    const batches = allBatches[barangId] || [];
    let summaryHtml = '';
    selected.forEach(s => {
        const b = batches.find(x => x.id == s.batch_id);
        if (!b) return;
        const cls = badgeClass(b.status);
        const ico = badgeIcon(b.status);
        const tgl = b.expired_date ?? 'No Exp';
        summaryHtml += `<span class="badge ${cls} me-1 mb-1">${ico} ${tgl} <small>(${s.qty})</small></span>`;
    });
    if (summaryHtml) summaryEl.innerHTML = summaryHtml;
}

// Update semua tampilan saat barang / qty berubah
function updateRow(row, barangId, qty) {
    const summaryEl  = row.querySelector('.expired-summary');
    const batchSel   = row.querySelector('.batch-selector');
    const toggleBtn  = row.querySelector('.btn-toggle-batch');

    if (!barangId || !allBatches[barangId]) {
        summaryEl.innerHTML = `<span class="text-muted small">— pilih barang dulu</span>`;
        batchSel.classList.remove('show');
        toggleBtn.style.display = 'none';
        return;
    }

    const fifo = simulateFifo(barangId, qty);
    renderSummary(fifo, summaryEl);
    renderBatchList(barangId, fifo, row);
    toggleBtn.style.display = 'inline-block';

    // Jika selector sudah terbuka, refresh tanpa menutup
    if (batchSel.classList.contains('show')) {
        renderBatchList(barangId, fifo, row);
    }
}

function bindRowEvents(row) {
    const selectBarang = row.querySelector('.select-barang');
    const inputQty     = row.querySelector('.input-qty');
    const inputHarga   = row.querySelector('.input-harga');
    const batchSel     = row.querySelector('.batch-selector');
    const toggleBtn    = row.querySelector('.btn-toggle-batch');
    const resetBtn     = row.querySelector('.btn-reset-fifo');

    selectBarang.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        inputHarga.value = opt.dataset.harga || 0;
        hitungSubtotal(row);
        updateRow(row, this.value, inputQty.value);
    });

    inputQty.addEventListener('input', () => {
        hitungSubtotal(row);
        updateRow(row, selectBarang.value, inputQty.value);
    });

    inputHarga.addEventListener('input', () => hitungSubtotal(row));

    toggleBtn.addEventListener('click', () => {
        batchSel.classList.toggle('show');
        toggleBtn.innerHTML = batchSel.classList.contains('show')
            ? '<i class="bi bi-chevron-up me-1"></i>Tutup pilihan batch'
            : '<i class="bi bi-sliders me-1"></i>Ubah pilihan batch';
    });

    resetBtn.addEventListener('click', () => {
        const fifo = simulateFifo(selectBarang.value, inputQty.value);
        renderBatchList(selectBarang.value, fifo, row);
    });

    row.querySelector('.btn-hapus-row').addEventListener('click', function () {
        if (document.querySelectorAll('#itemBody tr').length > 1) {
            row.remove(); hitungTotal();
        }
    });
}

document.getElementById('btnTambahRow').addEventListener('click', function () {
    const tbody = document.getElementById('itemBody');
    const tr    = document.createElement('tr');
    tr.id = 'row-' + rowIndex;
    tr.innerHTML = `
        <td><select name="items[${rowIndex}][barang_id]" class="form-select form-select-sm select-barang" required>
            <option value="">-- Pilih Barang --</option>${barangOptions}</select></td>
        <td><input type="number" name="items[${rowIndex}][qty]" class="form-control form-control-sm input-qty" min="1" value="1" required></td>
        <td><input type="number" name="items[${rowIndex}][harga_jual]" class="form-control form-control-sm input-harga" min="0" value="0" required></td>
        <td>
            <div class="expired-summary text-muted small">— pilih barang dulu</div>
            <div class="batch-selector">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold small"><i class="bi bi-boxes me-1"></i>Pilih Batch Manual</span>
                    <button type="button" class="btn btn-xs btn-outline-secondary btn-reset-fifo py-0 px-2" style="font-size:11px;">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset FIFO
                    </button>
                </div>
                <div class="batch-list"></div>
                <div class="text-danger small mt-1 batch-warning" style="display:none">
                    <i class="bi bi-exclamation-triangle"></i> Total qty batch melebihi qty item!
                </div>
            </div>
            <button type="button" class="btn btn-link btn-sm p-0 mt-1 btn-toggle-batch" style="font-size:12px; display:none;">
                <i class="bi bi-sliders me-1"></i>Ubah pilihan batch
            </button>
            <div class="batch-hidden-inputs"></div>
        </td>
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