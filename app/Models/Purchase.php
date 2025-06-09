<?php

namespace App\Models;

use App\Models\User;
use App\Models\Supplier;
use App\Models\PurchaseDetail;
use App\Models\PurchaseApprovalHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'invoice_number',
        'purchase_date',
        'total_amount',
        'notes',
        'status',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'rejection_reason',
        'received_at',
        'received_by'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'received_at' => 'datetime'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function details()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function approvalHistory()
    {
        return $this->hasMany(PurchaseApprovalHistory::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function generateInvoiceNumber()
    {
        $prefix = 'PO';
        $date = now()->format('Ymd');
        $lastPurchase = self::where('invoice_number', 'like', "{$prefix}{$date}%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastPurchase) {
            $lastNumber = (int) substr($lastPurchase->invoice_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}{$date}{$newNumber}";
    }
} 