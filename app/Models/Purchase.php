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
        'pemasok_id',
        'tanggal_pembelian',
        'nomor_pembelian',
        'total_amount',
        'catatan',
        'status_pembelian',
        'disetujui_pada',
        'disetujui_oleh',
        'ditolak_pada',
        'ditolak_oleh',
        'alasan_penolakan',
        'diterima_pada',
        'diterima_oleh'
    ];

    protected $casts = [
        'tanggal_pembelian' => 'date',
        'disetujui_pada' => 'datetime',
        'ditolak_pada' => 'datetime',
        'diterima_pada' => 'datetime'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'pemasok_id');
    }

    public function details()
    {
        return $this->hasMany(PurchaseDetail::class, 'pembelian_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'ditolak_oleh');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'diterima_oleh');
    }

    public function approvalHistory()
    {
        return $this->hasMany(PurchaseApprovalHistory::class, 'pembelian_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
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