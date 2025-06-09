<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'pelanggan_id',
        'tanggal_pesanan',
        'status_pesanan',
        'total_produk',
        'sub_total',
        'pajak',
        'total',
        'nomor_faktur',
        'jenis_pembayaran',
        'bayar',
        'jatuh_tempo'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'pelanggan_id');
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'pesanan_id');
    }
} 