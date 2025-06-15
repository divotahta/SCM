<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pembelian_id',
        'produk_id',
        'jumlah',
        'harga_satuan',
        'total',
        'catatan'
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