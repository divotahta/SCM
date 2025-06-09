<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'payment_method',
        'payment_amount',
        'total',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            $transaction->invoice_number = static::generateInvoiceNumber();
        });
    }

    public static function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $lastTransaction = static::where('invoice_number', 'like', "{$prefix}{$date}%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastTransaction) {
            $lastNumber = (int) substr($lastTransaction->invoice_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}{$date}{$newNumber}";
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function logs()
    {
        return $this->hasMany(TransactionLog::class);
    }

    public function void()
    {
        if ($this->status !== 'completed') {
            throw new \Exception('Hanya transaksi yang sudah selesai yang dapat dibatalkan');
        }

        DB::transaction(function () {
            // Kembalikan stok
            foreach ($this->details as $detail) {
                $product = $detail->product;
                $product->increment('stock', $detail->quantity);
            }

            // Update status transaksi
            $this->update(['status' => 'void']);

            // Log aktivitas
            $this->logs()->create([
                'user_id' => Auth::id(),
                'action' => 'void',
                'description' => 'Transaksi dibatalkan',
                'old_data' => ['status' => 'completed'],
                'new_data' => ['status' => 'void']
            ]);
        });
    }
} 