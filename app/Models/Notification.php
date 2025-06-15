<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'jenis',
        'dibaca',
        'detail',
        "link",
    ];

    protected $casts = [
        'dibaca' => 'boolean',
        'detail' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 