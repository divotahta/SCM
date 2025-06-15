<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Product;
use App\Models\StockHistory;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('Admin.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->update(['dibaca' => true]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('dibaca', false)
            ->update(['dibaca' => true]);

        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->where('dibaca', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function getNotifications()
    {
        try {
            $notifications = Notification::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'judul' => $notification->judul,
                        'pesan' => $notification->pesan,
                        'dibaca' => $notification->dibaca,
                        'created_at' => $notification->created_at,
                        'link' => $notification->link
                    ];
                });

            $unreadCount = Notification::where('user_id', Auth::id())
                ->where('dibaca', false)
                ->count();

            Log::info('Notifications fetched', [
                'user_id' => Auth::id(),
                'count' => $notifications->count(),
                'unread' => $unreadCount,
                'notifications' => $notifications->toArray()
            ]);

            return response()->json([
                'notifications' => $notifications,
                'unreadCount' => $unreadCount
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching notifications', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'notifications' => [],
                'unreadCount' => 0,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkStockLevels()
    {
        $products = Product::all();
        $notifications = [];

        foreach ($products as $product) {
            // Hitung rata-rata penggunaan per minggu
            $weeklyUsage = $this->calculateWeeklyUsage($product);
            
            if ($weeklyUsage > 0) {
                // Hitung sisa stok dalam minggu
                $remainingWeeks = $product->stok / $weeklyUsage;
                
                // Hitung EOQ
                $eoq = $this->calculateEOQ($product, $weeklyUsage);
                
                // Hitung reorder point (ROP)
                $rop = $this->calculateROP($product, $weeklyUsage);
                
                if ($remainingWeeks <= 2) {
                    $notification = Notification::create([
                        'user_id' => Auth::id(),
                        'judul' => 'Stok Menipis',
                        'pesan' => "Stok {$product->nama} akan habis dalam " . round($remainingWeeks, 1) . " minggu. Disarankan untuk melakukan pembelian sebesar {$eoq} {$product->satuan}.",
                        'jenis' => 'stock_alert',
                        'detail' => [
                            'produk_id' => $product->id,
                            'stok_lama' => $product->stok,
                            'stok_baru' => $product->stok,
                            'jumlah' => $weeklyUsage,
                            'sisa_minggu' => $remainingWeeks,
                            'eoq' => $eoq,
                            'rop' => $rop
                        ]
                    ]);

                    $notifications[] = $notification;
                }
            }
        }

        return $notifications;
    }

    private function calculateWeeklyUsage($product)
    {
        // Ambil data 3 bulan terakhir
        $threeMonthsAgo = Carbon::now()->subMonths(3);
        
        $totalUsage = StockHistory::where('produk_id', $product->id)
            ->where('jenis', 'keluar')
            ->where('created_at', '>=', $threeMonthsAgo)
            ->sum('jumlah');

        // Hitung rata-rata per minggu
        $weeks = 12; // 3 bulan = 12 minggu
        return $totalUsage / $weeks;
    }

    private function calculateEOQ($product, $weeklyUsage)
    {
        // Konversi weekly usage ke annual demand
        $annualDemand = $weeklyUsage * 52;
        
        // Biaya pemesanan (order cost) - bisa disesuaikan
        $orderCost = 50000;
        
        // Biaya penyimpanan per unit per tahun (holding cost)
        // Menggunakan 20% dari harga beli sebagai holding cost
        $holdingCost = $product->harga_beli * 0.2;
        
        // Hitung EOQ
        $eoq = sqrt((2 * $annualDemand * $orderCost) / $holdingCost);
        
        return round($eoq);
    }

    private function calculateROP($product, $weeklyUsage)
    {
        // Lead time dalam minggu (bisa disesuaikan)
        $leadTime = 1;
        
        // Safety stock (bisa disesuaikan)
        $safetyStock = $weeklyUsage * 0.5;
        
        // Hitung ROP
        $rop = ($weeklyUsage * $leadTime) + $safetyStock;
        
        return round($rop);
    }
} 