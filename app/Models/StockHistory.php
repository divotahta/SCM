<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;

    protected $table = 'stock_history';

    protected $fillable = [
        'produk_id',
        'jenis',
        'jumlah',
        'stok_lama',
        'stok_baru',
        'keterangan',
        'dibuat_oleh'
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'stok_lama' => 'integer',
        'stok_baru' => 'integer'
    ];

    public function produk()
    {
        return $this->belongsTo(Product::class, 'produk_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
} 