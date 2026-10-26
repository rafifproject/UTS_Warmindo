<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($kategori) {
            if (empty($kategori->slug)) {
                $kategori->slug = Str::slug($kategori->name);
            }
        });
    }

    public function produk()
    {
        return $this->hasMany(Produk::class, 'category_id');
    }
}
