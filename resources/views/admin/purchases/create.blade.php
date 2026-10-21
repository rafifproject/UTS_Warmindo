@extends('layouts.admin')

@section('title', 'Form Pembelian Stok Masuk — Warmindo')
@section('page_title', 'Form Pembelian Stok Masuk (Banyak Item)')

@section('content')
<div class="card" style="max-width: 960px; margin: 0 auto; padding: 36px;">
  <!-- Header (Screen 27) -->
  <div style="margin-bottom: 24px; border-bottom: 1px solid var(--border-light); padding-bottom: 16px;">
    <h3 style="font-size: 18px; font-weight: 800; color: var(--text-dark); margin-bottom: 4px;">Form Pembelian Banyak Item</h3>
    <p style="font-size: 13px; color: var(--text-muted); margin: 0;">Tambah pasokan bahan/menu. Stok produk akan bertambah secara atomik setelah transaksi disimpan.</p>
  </div>

  @if($errors->any())
    <div style="background-color: #FFE3E3; border-left: 4px solid #FA5252; color: #E03131; padding: 12px 18px; border-radius: var(--radius-md); font-weight: 600; margin-bottom: 24px;">
      {{ $errors->first() }}
    </div>
  @endif

  <form action="{{ route('admin.purchases.store') }}" method="POST">
    @csrf

    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 20px;">
      <div class="form-group" style="flex: 1; min-width: 220px;">
        <label class="form-label" for="purchase_number">Nomor Transaksi Pembelian <span class="required">*</span></label>
        <input type="text" name="purchase_number" id="purchase_number" value="{{ old('purchase_number', $nextNumber) }}" class="form-control" required>
      </div>

      <div class="form-group" style="flex: 1.5; min-width: 260px;">
        <label class="form-label" for="supplier_id">Pilih Supplier <span class="required">*</span></label>
        <select name="supplier_id" id="supplier_id" class="form-control form-select" required>
          <option value="">-- Pilih Supplier Pemasok --</option>
          @foreach($suppliers as $sup)
            <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->name }} ({{ $sup->contact_person ?? 'PIC' }})</option>
          @endforeach
        </select>
      </div>

      <div class="form-group" style="flex: 1; min-width: 180px;">
        <label class="form-label" for="purchase_date">Tanggal Pembelian <span class="required">*</span></label>
        <input type="date" name="purchase_date" id="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}" class="form-control" required>
      </div>
    </div>

    <!-- Multi-Item Rows Table (Screen 27) -->
    <div style="margin-bottom: 20px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <h4 style="font-size: 15px; font-weight: 800; color: var(--text-dark);">Daftar Item Barang Masuk:</h4>
        <button type="button" class="btn btn-secondary btn-sm" onclick="addPurchaseRow()">
          + Tambah Baris Item
        </button>
      </div>

      <div class="table-responsive">
        <table class="table" id="purchaseItemsTable" style="margin: 0;">
          <thead>
            <tr>
              <th style="width: 40%;">Produk Menu <span class="required">*</span></th>
              <th style="width: 20%; text-align: center;">Jumlah (Qty) <span class="required">*</span></th>
              <th style="width: 25%; text-align: right;">Harga Beli Satuan <span class="required">*</span></th>
              <th style="width: 15%; text-align: center;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <!-- Initial Row 1 -->
            <tr class="item-row">
              <td>
                <select name="items[0][product_id]" class="form-control form-select product-select" required onchange="handleProductSelect(this)">
                  <option value="">-- Pilih Produk --</option>
                  @foreach($products as $p)
                    <option value="{{ $p->id }}" data-price="{{ (int)$p->buy_price }}">{{ $p->name }} (Stok saat ini: {{ $p->stock }})</option>
                  @endforeach
                </select>
              </td>
              <td>
                <input type="number" name="items[0][quantity]" class="form-control item-qty" value="10" min="1" required style="text-align: center;" oninput="updateGrandTotal()">
              </td>
              <td>
                <input type="number" name="items[0][unit_price]" class="form-control item-price" value="8000" min="0" required style="text-align: right;" oninput="updateGrandTotal()">
              </td>
              <td style="text-align: center;">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)" disabled style="opacity: 0.5;">Hapus</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Total Computation Summary Card (Screen 27) -->
    <div style="background-color: #F8F9FA; border-radius: var(--radius-md); padding: 20px; border: 1px solid var(--border-light); margin-bottom: 24px; display: flex; justify-content: space-between; align-items: baseline;">
      <div>
        <span style="font-size: 13px; color: var(--text-muted);">Total Perkiraan Biaya Pasokan:</span>
        <div style="font-size: 13.5px; color: var(--text-body); font-weight: 600;">Petugas: {{ auth()->user()->name ?? 'Admin' }}</div>
      </div>
      <div style="text-align: right;">
        <span style="font-size: 14px; font-weight: 700; color: var(--text-dark); margin-right: 12px;">Total Transaksi:</span>
        <span id="grandTotalText" style="font-size: 26px; font-weight: 800; color: var(--primary);">Rp 80.000</span>
      </div>
    </div>

    <div class="form-group" style="margin-bottom: 24px;">
      <label class="form-label" for="notes">Catatan Pembelian (opsional)</label>
      <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Nomor faktur supplier, keterangan jatuh tempo, dll.">{{ old('notes') }}</textarea>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center;">
      <a href="{{ route('admin.purchases.index') }}" class="btn btn-secondary">
        Batal
      </a>
      <button type="submit" class="btn btn-primary btn-lg">
        Konfirmasi Pembelian &amp; Update Stok &rarr;
      </button>
    </div>
  </form>
</div>

@push('scripts')
<script>
  let rowIdx = 1;

  function addPurchaseRow() {
    const tbody = document.querySelector('#purchaseItemsTable tbody');
    const newRow = document.createElement('tr');
    newRow.className = 'item-row';
    newRow.innerHTML = `
      <td>
        <select name="items[${rowIdx}][product_id]" class="form-control form-select product-select" required onchange="handleProductSelect(this)">
          <option value="">-- Pilih Produk --</option>
          @foreach($products as $p)
            <option value="{{ $p->id }}" data-price="{{ (int)$p->buy_price }}">{{ $p->name }} (Stok saat ini: {{ $p->stock }})</option>
          @endforeach
        </select>
      </td>
      <td>
        <input type="number" name="items[${rowIdx}][quantity]" class="form-control item-qty" value="10" min="1" required style="text-align: center;" oninput="updateGrandTotal()">
      </td>
      <td>
        <input type="number" name="items[${rowIdx}][unit_price]" class="form-control item-price" value="0" min="0" required style="text-align: right;" oninput="updateGrandTotal()">
      </td>
      <td style="text-align: center;">
        <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">Hapus</button>
      </td>
    `;
    tbody.appendChild(newRow);
    rowIdx++;
    updateGrandTotal();
  }

  function removeRow(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) {
      btn.closest('tr').remove();
      updateGrandTotal();
    }
  }

  function handleProductSelect(select) {
    const selectedOpt = select.options[select.selectedIndex];
    const price = selectedOpt.getAttribute('data-price') || 0;
    const row = select.closest('tr');
    const priceInput = row.querySelector('.item-price');
    if (priceInput && price > 0) {
      priceInput.value = price;
    }
    updateGrandTotal();
  }

  function updateGrandTotal() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach(row => {
      const qty = parseFloat(row.querySelector('.item-qty').value || 0);
      const price = parseFloat(row.querySelector('.item-price').value || 0);
      total += (qty * price);
    });
    document.getElementById('grandTotalText').textContent = 'Rp ' + total.toLocaleString('id-ID');
  }

  updateGrandTotal();
</script>
@endpush
@endsection
