<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'pesanan_id',
        'produk_id',
        'jumlah',
        'harga_satuan',
        'total'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'pesanan_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'produk_id');
    }
} 