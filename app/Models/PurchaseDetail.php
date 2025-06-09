<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $fillable = [
        'pembelian_id',
        'produk_id',
        'jumlah',
        'harga_satuan',
        'total'
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'pembelian_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'produk_id');
    }
} 