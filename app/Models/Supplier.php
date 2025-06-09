<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'alamat',
        'nama_toko',
        'jenis',
        'nama_bank',
        'pemegang_rekening',
        'nomor_rekening',
        'foto'
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'pemasok_id');
    }
} 