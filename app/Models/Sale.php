<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

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
        'subtotal' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'cash_received' => 'decimal:2',
        'cash_change' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class)->latest();
    }

    public function scopePendingPayment($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeKitchenQueue($query)
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
        return $this->items->map(function ($item) {
            return $item->product_name . ($item->quantity > 1 ? ' (' . $item->quantity . 'x)' : '');
        })->join(', ');
    }
}
