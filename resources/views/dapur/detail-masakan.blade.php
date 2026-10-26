@extends('layouts.kitchen')

@section('title', 'Detail Pesanan Dapur #' . $sale->sale_number)

@section('content')
<div style="max-width: 960px; margin: 0 auto;">
  <div style="margin-bottom: 20px;">
    <a href="{{ route('kitchen.dashboard') }}" style="font-size: 13.5px; font-weight: 600; color: var(--primary);">
      &larr; Kembali ke Antrean Dapur
    </a>
  </div>

  <div class="card" style="padding: 32px; margin-bottom: 24px;">
    <!-- Order Header with Badge Lunas (Screen 58) -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; border-bottom: 1px solid var(--border-light); padding-bottom: 20px;">
      <div>
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
          <h1 style="font-size: 32px; font-weight: 800; color: var(--primary); margin: 0;">#{{ $sale->sale_number }}</h1>
          <span class="badge badge-green" style="font-size: 13px; padding: 6px 14px;">
            <span class="badge-dot"></span> Lunas
          </span>
        </div>
        <div style="display: flex; gap: 16px; font-size: 13.5px; color: var(--text-body);">
          <span><strong>Layanan:</strong> {{ $sale->order_type === 'dine_in' ? 'Meja ' . $sale->table_number . ' (Dine In)' : 'Take Away' }}</span>
          <span>&bull;</span>
          <span><strong>Jumlah:</strong> {{ $sale->items->sum('quantity') }} Item</span>
          <span>&bull;</span>
          <span><strong>Waktu Bayar:</strong> {{ $sale->paid_at ? $sale->paid_at->format('d M Y H:i') : '-' }}</span>
        </div>
      </div>
    </div>

    <!-- Stepper Progress Bar (Screen 58 & 59) -->
    <div style="margin-bottom: 36px; padding: 0 20px;">
      <div class="order-stepper">
        <!-- Step 1: Diterima -->
        <div class="step-item {{ in_array($sale->order_status, ['queued', 'preparing', 'ready_to_serve', 'completed']) ? 'completed' : '' }}">
          <div class="step-circle">1</div>
          <span class="step-label">Diterima</span>
        </div>

        <!-- Step 2: Diproses -->
        <div class="step-item {{ in_array($sale->order_status, ['preparing', 'ready_to_serve', 'completed']) ? ($sale->order_status === 'preparing' ? 'active' : 'completed') : '' }}">
          <div class="step-circle">2</div>
          <span class="step-label">Diproses</span>
        </div>

        <!-- Step 3: Siap Disajikan -->
        <div class="step-item {{ in_array($sale->order_status, ['ready_to_serve', 'completed']) ? ($sale->order_status === 'ready_to_serve' ? 'active' : 'completed') : '' }}">
          <div class="step-circle">3</div>
          <span class="step-label">Siap Disajikan</span>
        </div>

        <!-- Step 4: Selesai -->
        <div class="step-item {{ $sale->order_status === 'completed' ? 'completed' : '' }}">
          <div class="step-circle">4</div>
          <span class="step-label">Selesai</span>
        </div>
      </div>
    </div>

    <!-- Itemized Cooking List (Screen 58 & 59) -->
    <h3 style="font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 14px;">Daftar Item Menu yang Dimasak:</h3>
    <div class="table-responsive" style="margin-bottom: 24px;">
      <table class="table">
        <thead>
          <tr>
            <th style="width: 50px;">No.</th>
            <th>Menu Masakan</th>
            <th style="text-align: center;">Jumlah</th>
            <th>Catatan Pelanggan</th>
            <th>Status Pengerjaan</th>
          </tr>
        </thead>
        <tbody>
          @foreach($sale->items as $idx => $it)
            <tr>
              <td>{{ $idx + 1 }}</td>
              <td style="font-weight: 700; font-size: 15px; color: var(--text-dark);">
                {{ $it->product_name }}
              </td>
              <td style="text-align: center; font-size: 16px; font-weight: 800; color: var(--primary);">
                &times; {{ $it->quantity }}
              </td>
              <td>
                @if($it->notes)
                  <span style="background-color: #FFF4E6; color: #D9480F; padding: 4px 10px; border-radius: 4px; font-size: 12.5px; font-weight: 600;">
                    {{ $it->notes }}
                  </span>
                @else
                  <span style="color: var(--text-muted);">-</span>
                @endif
              </td>
              <td>
                <span class="badge {{ $sale->order_status === 'completed' ? 'badge-green' : ($sale->order_status === 'ready_to_serve' ? 'badge-yellow' : 'badge-orange') }}">
                  {{ strtoupper(str_replace('_', ' ', $sale->order_status)) }}
                </span>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- General Order Note -->
    @if($sale->notes)
      <div style="background-color: #FFF9DB; border-left: 4px solid #F59F00; padding: 14px 18px; border-radius: var(--radius-md); margin-bottom: 28px;">
        <h5 style="font-size: 13.5px; font-weight: 700; color: #754400; margin-bottom: 4px;">Catatan Khusus Pesanan:</h5>
        <p style="font-size: 14px; color: #754400; margin: 0;">{{ $sale->notes }}</p>
      </div>
    @endif

    <!-- Form Pembaruan Status Masakan (Screen 59) -->
    <div style="background-color: #F8F9FA; border-radius: var(--radius-lg); padding: 24px; border: 1px solid var(--border-light);">
      <h4 style="font-size: 15px; font-weight: 800; color: var(--text-dark); margin-bottom: 14px;">Perbarui Status Pengerjaan:</h4>
      
      <div style="display: flex; flex-wrap: wrap; gap: 12px;">
        @if($sale->order_status === 'queued')
          <form action="{{ route('kitchen.status.update', $sale->sale_number) }}" method="POST" style="flex: 1;">
            @csrf
            <input type="hidden" name="status" value="preparing">
            <button type="submit" class="btn btn-primary btn-block btn-lg">
              Mulai Memasak (Diproses) &rarr;
            </button>
          </form>
        @elseif($sale->order_status === 'preparing')
          <form action="{{ route('kitchen.status.update', $sale->sale_number) }}" method="POST" style="flex: 1;">
            @csrf
            <input type="hidden" name="status" value="ready_to_serve">
            <button type="submit" class="btn btn-success btn-block btn-lg">
              Selesai Masak (Siap Disajikan) &rarr;
            </button>
          </form>
        @elseif($sale->order_status === 'ready_to_serve')
          <form action="{{ route('kitchen.status.update', $sale->sale_number) }}" method="POST" style="flex: 1;">
            @csrf
            <input type="hidden" name="status" value="completed">
            <button type="submit" class="btn btn-primary btn-block btn-lg" style="background-color: #1971C2;">
              Tandai Telah Diantar ke Meja (Selesai) &check;
            </button>
          </form>
        @else
          <div style="background-color: #E6FCF5; color: #0CA678; font-weight: 700; padding: 12px 18px; border-radius: var(--radius-md); width: 100%; text-align: center;">
            &check; Pesanan ini telah tuntas disajikan.
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
