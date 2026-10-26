<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'purchases';

    protected $fillable = [
        'purchase_number',
        'supplier_id',
        'user_id',
        'purchase_date',
        'total_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'total_amount'  => 'decimal:2',
    ];

    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class, 'supplier_id');
    }

    public function supplier()
    {
        return $this->pemasok();
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }

    public function user()
    {
        return $this->pengguna();
    }

    public function itemPembelian()
    {
        return $this->hasMany(ItemPembelian::class, 'purchase_id');
    }

    public function items()
    {
        return $this->itemPembelian();
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }
}
