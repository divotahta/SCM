<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'nama_produk',
        'kategori_id',
        'kode_produk',
        'harga_beli',
        'harga_jual',
        'stok',
        'unit_id',
        'gambar_produk'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class, 'produk_id');
    }

    // public function stockHistories()
    // {
    //     return $this->hasMany(StockHistory::class);
    // }
    public function stockHistories()
{
    return $this->hasMany(StockHistory::class, 'produk_id'); // sebutkan foreign key secara eksplisit
}

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'produk_id');
    }
} 