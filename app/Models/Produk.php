<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Produk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'slug',
        'description',
        'buy_price',
        'sell_price',
        'stock',
        'min_stock',
        'image',
        'is_available',
        'is_favorite',
    ];

    protected $casts = [
        'buy_price'    => 'decimal:2',
        'sell_price'   => 'decimal:2',
        'stock'        => 'integer',
        'min_stock'    => 'integer',
        'is_available' => 'boolean',
        'is_favorite'  => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($produk) {
            if (empty($produk->slug)) {
                $produk->slug = Str::slug($produk->name) . '-' . Str::lower(Str::random(4));
            }
        });
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'category_id');
    }

    public function category()
    {
        return $this->kategori();
    }

    public function itemPenjualan()
    {
        return $this->hasMany(ItemPenjualan::class, 'product_id');
    }

    public function itemPembelian()
    {
        return $this->hasMany(ItemPembelian::class, 'product_id');
    }

    public function mutasiStok()
    {
        return $this->hasMany(MutasiStok::class, 'product_id')->latest();
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    public function getFormattedSellPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->sell_price, 0, ',', '.');
    }

    public function getFormattedBuyPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->buy_price, 0, ',', '.');
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image) . '?v=' . filemtime(public_path($this->image));
        }
        if ($this->image && str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        return asset('images/default-menu.png');
    }
}
