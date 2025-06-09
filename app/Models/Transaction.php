<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction';

    protected $fillable = [
        'kode_transaksi',
        'customer_id',
        'user_id',
        'total_harga',
        'total_bayar',
        'total_kembali',
        'metode_pembayaran',
        'status',
        'catatan'
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
        'total_bayar' => 'decimal:2',
        'total_kembali' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            $transaction->kode_transaksi = static::generateInvoiceNumber();
        });
    }

    public static function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $lastTransaction = static::where('kode_transaksi', 'like', "{$prefix}{$date}%")
            ->orderBy('kode_transaksi', 'desc')
            ->first();

        if ($lastTransaction) {
            $lastNumber = (int) substr($lastTransaction->kode_transaksi, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}{$date}{$newNumber}";
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaksi_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'detail_transaksi', 'transaksi_id', 'produk_id')
            ->withPivot('jumlah', 'harga', 'subtotal')
            ->withTimestamps();
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