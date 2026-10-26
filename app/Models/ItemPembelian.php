<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPembelian extends Model
{
    use HasFactory;

    protected $table = 'purchase_items';

    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity'   => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal'   => 'decimal:2',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'purchase_id');
    }

    public function purchase()
    {
        return $this->pembelian();
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'product_id')->withTrashed();
    }

    public function product()
    {
        return $this->produk();
    }

    public function getFormattedUnitPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->unit_price, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}
