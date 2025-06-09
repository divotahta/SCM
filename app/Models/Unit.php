<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['nama_satuan'];

    public function products()
    {
        return $this->hasMany(Product::class, 'unit_id');
    }
} 