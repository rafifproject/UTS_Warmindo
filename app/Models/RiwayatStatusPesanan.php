<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatStatusPesanan extends Model
{
    use HasFactory;

    protected $table = 'order_status_histories';

    protected $fillable = [
        'sale_id',
        'from_status',
        'to_status',
        'staff_name',
        'user_id',
        'notes',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'sale_id');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }
}
