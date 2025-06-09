<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseApprovalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'status',
        'notes',
        'created_by'
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
} 