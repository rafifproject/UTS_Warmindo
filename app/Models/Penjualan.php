<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'sales';

    protected $fillable = [
        'sale_number',
        'user_id',
        'customer_name',
        'order_type',
        'table_number',
        'subtotal',
        'service_fee',
        'total_amount',
        'payment_status',
        'order_status',
        'payment_method',
        'cash_received',
        'cash_change',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'subtotal'      => 'decimal:2',
        'service_fee'   => 'decimal:2',
        'total_amount'  => 'decimal:2',
        'cash_received' => 'decimal:2',
        'cash_change'   => 'decimal:2',
        'paid_at'       => 'datetime',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }

    public function user()
    {
        return $this->pengguna();
    }

    public function itemPenjualan()
    {
        return $this->hasMany(ItemPenjualan::class, 'sale_id');
    }

    public function items()
    {
        return $this->itemPenjualan();
    }

    public function riwayatStatus()
    {
        return $this->hasMany(RiwayatStatusPesanan::class, 'sale_id')->latest();
    }

    public function scopeMenungguPembayaran($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeLunas($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeAntreanDapur($query)
    {
        return $query->where('payment_status', 'paid')
                     ->whereIn('order_status', ['queued', 'preparing', 'ready_to_serve']);
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getFormattedCashReceivedAttribute(): string
    {
        return $this->cash_received ? 'Rp ' . number_format($this->cash_received, 0, ',', '.') : '-';
    }

    public function getFormattedCashChangeAttribute(): string
    {
        return $this->cash_change !== null ? 'Rp ' . number_format($this->cash_change, 0, ',', '.') : '-';
    }

    public function getItemsSummaryAttribute(): string
    {
        return $this->itemPenjualan->map(function ($item) {
            return $item->product_name . ($item->quantity > 1 ? ' (' . $item->quantity . 'x)' : '');
        })->join(', ');
    }
}
