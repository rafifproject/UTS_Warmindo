<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasok extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function pembelian()
    {
        return $this->hasMany(Pembelian::class, 'supplier_id')->latest();
    }

    public function purchases()
    {
        return $this->pembelian();
    }
}
