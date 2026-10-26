<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPenjualan extends Model
{
    use HasFactory;

    protected $table = 'sale_items';

    protected $fillable = [
        'sale_id',
        'product_id',
        'product_name',
        'price',
        'quantity',
        'subtotal',
        'notes',
        'status',
    ];

    protected $casts = [
        'price'    => 'decimal:2',
        'quantity' => 'integer',
        'subtotal' => 'decimal:2',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'sale_id');
    }

    public function sale()
    {
        return $this->penjualan();
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'product_id')->withTrashed();
    }

    public function product()
    {
        return $this->produk();
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}
