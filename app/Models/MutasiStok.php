<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiStok extends Model
{
    use HasFactory;

    protected $table = 'stock_mutations';

    protected $fillable = [
        'product_id',
        'type',
        'reference_type',
        'reference_id',
        'reference_number',
        'quantity',
        'before_stock',
        'after_stock',
        'notes',
        'user_id',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'product_id')->withTrashed();
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }
}
