<?php

namespace App\Models;

use App\Models\User;
use App\Models\Supplier;
use App\Models\PurchaseDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pemasok_id',
        'tanggal_pembelian',
        'nomor_pembelian',
        'total_amount',
        'catatan',
        'status_pembelian',
        'dibuat_oleh',
        'disetujui_oleh',
        'disetujui_pada',
        'ditolak_oleh',
        'ditolak_pada',
        'alasan_penolakan',
        'diterima_oleh',
        'diterima_pada'
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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function generateInvoiceNumber()
    {
        $lastPurchase = $this->orderBy('id', 'desc')->first();
        $lastNumber = $lastPurchase ? intval(substr($lastPurchase->nomor_pembelian, 3)) : 0;
        $newNumber = $lastNumber + 1;
        return 'PUR' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }
} 