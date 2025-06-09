<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'telepon',
        'alamat',
        'foto',
        'total_pembelian',
        'loyalty_level',
        'points',
        'last_purchase_at'
    ];

    protected $casts = [
        'points' => 'integer',
        'total_purchase' => 'decimal:2',
        'last_purchase_at' => 'datetime'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'pelanggan_id');
    }

    public function getOutstandingPaymentAttribute()
    {
        return $this->transactions()
            ->where('status', '!=', 'paid')
            ->sum('grand_total');
    }

    public function getLoyaltyDiscountAttribute()
    {
        return [
            'bronze' => 0,
            'silver' => 5,
            'gold' => 10,
            'platinum' => 15
        ][$this->loyalty_level] ?? 0;
    }

    public function updateLoyaltyLevel()
    {
        $totalPurchase = $this->total_purchase;
        
        if ($totalPurchase >= 10000000) {
            $this->loyalty_level = 'platinum';
        } elseif ($totalPurchase >= 5000000) {
            $this->loyalty_level = 'gold';
        } elseif ($totalPurchase >= 1000000) {
            $this->loyalty_level = 'silver';
        } else {
            $this->loyalty_level = 'bronze';
        }

        $this->save();
    }

    public function addPoints($amount)
    {
        // 1 point per Rp 10.000
        $points = floor($amount / 10000);
        $this->points += $points;
        $this->save();
    }

    public function usePoints($points)
    {
        if ($this->points >= $points) {
            $this->points -= $points;
            $this->save();
            return true;
        }
        return false;
    }

    public function getPointsValue()
    {
        // 1 point = Rp 1.000
        return $this->points * 1000;
    }
} 