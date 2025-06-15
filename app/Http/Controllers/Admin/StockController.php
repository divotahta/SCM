<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Category;
use Milon\Barcode\DNS1D;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use App\Models\StockAdjustment;
use Illuminate\Support\Facades\DB;
use App\Exports\StockForecastExport;
use App\Exports\StockMovementExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\PDF;
use Maatwebsite\Excel\Facades\Excel;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'unit'])
            ->when($request->search, function($q) use ($request) {
                return $q->where('nama_produk', 'like', "%{$request->search}%")
                    ->orWhere('kode_produk', 'like', "%{$request->search}%");
            })
            ->when($request->kategori_id, function($q) use ($request) {
                return $q->where('kategori_id', $request->kategori_id);
            })
            ->when($request->status_stok, function($q) use ($request) {
                if ($request->status_stok === 'low') {
                    return $q->where('stok', '<=', 10);
                } elseif ($request->status_stok === 'out') {
                    return $q->where('stok', 0);
                }
                return $q;
            });

        $products = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('Admin.stocks.index', compact('products', 'categories'));
    }

    public function history(Request $request)
    {
        $query = StockHistory::with(['produk', 'user'])
            ->when($request->search, function($q) use ($request) {
                return $q->whereHas('produk', function($q) use ($request) {
                    $q->where('nama_produk', 'like', "%{$request->search}%")
                        ->orWhere('kode_produk', 'like', "%{$request->search}%");
                });
            })
            ->when($request->type, function($q) use ($request) {
                return $q->where('type', $request->type);
            })
            ->when($request->date_start && $request->date_end, function($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_start, $request->date_end]);
            });

        $histories = $query->latest()->paginate(10);

        return view('Admin.stocks.history', compact('histories'));
    }

    public function adjust(Request $request, Product $product)
    {
        $request->validate([
            'jenis' => 'required|in:masuk,keluar',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'required|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            $quantity = $request->jumlah;
            $type = $request->jenis;
            $oldStock = $product->stok;

            if ($type === 'keluar' && $quantity > $oldStock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah pengurangan tidak boleh melebihi stok saat ini'
                ], 422);
            }

            $newStock = $type === 'masuk' ? $oldStock + $quantity : $oldStock - $quantity;
            
            $product->update(['stok' => $newStock]);

            // Catat history penyesuaian
            StockHistory::create([
                'produk_id' => $product->id,
                'jenis' => $type,
                'jumlah' => $quantity,
                'stok_lama' => $oldStock,
                'stok_baru' => $newStock,
                'keterangan' => $request->keterangan,
                'dibuat_oleh' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stok berhasil disesuaikan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyesuaikan stok: ' . $e->getMessage()
            ], 500);
        }
    }

    public function report(Request $request)
    {
        $query = StockHistory::with(['produk', 'user']);

        // Filter berdasarkan tanggal
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        // Filter berdasarkan produk
        if ($request->has('produk_id')) {
            $query->where('produk_id', $request->produk_id);
        }

        $histories = $query->get();

        if ($request->has('export')) {
            if ($request->export == 'excel') {
                return Excel::download(new StockMovementExport($histories), 'laporan-pergerakan-stok.xlsx');
            } else {
                $pdf = PDF::loadView('admin.stocks.report-pdf', compact('histories'));
                return $pdf->download('laporan-pergerakan-stok.pdf');
            }
        }

        return view('admin.stocks.report', compact('histories'));
    }

    public function forecast(Request $request)
    {
        $query = Product::with(['category', 'unit'])
            ->when($request->kategori_id, function($q) use ($request) {
                return $q->where('kategori_id', $request->kategori_id);
            });

        $products = $query->get();
        $categories = Category::all();

        // Hitung forecast untuk setiap produk
        foreach ($products as $product) {
            // Hitung rata-rata penjualan mingguan
            $weeklySales = $product->transactionDetails()
                ->whereBetween('created_at', [now()->subWeeks(4), now()])
                ->selectRaw('WEEK(created_at) as week, SUM(jumlah) as total')
                ->groupBy('week')
                ->get();

            // Hitung rata-rata penjualan per minggu
            $avgWeeklySales = $weeklySales->avg('total') ?? 0;

            // Hitung standar deviasi penjualan mingguan
            $stdDev = 0;
            if ($weeklySales->count() > 0) {
                $mean = $weeklySales->avg('total');
                $variance = $weeklySales->sum(function($sale) use ($mean) {
                    return pow($sale->total - $mean, 2);
                }) / $weeklySales->count();
                $stdDev = sqrt($variance);
            }

            // Hitung lead time (dalam hari)
            $leadTime = 7; // 7 hari

            // Hitung safety stock dengan service level 95%
            $serviceLevel = 1.645; // Z-score untuk 95% service level
            $safetyStock = $serviceLevel * $stdDev * sqrt($leadTime/7);

            // Hitung reorder point
            $reorderPoint = ($avgWeeklySales * ($leadTime/7)) + $safetyStock;

            // Hitung economic order quantity (EOQ)
            $orderCost = 100000; // Biaya pemesanan
            $holdingCost = 0.2; // Biaya penyimpanan (20% dari harga)
            $annualDemand = $avgWeeklySales * 52; // 52 minggu dalam setahun
            
            if ($product->harga_beli > 0 && $holdingCost > 0) {
                $eoq = sqrt((2 * $annualDemand * $orderCost) / ($product->harga_beli * $holdingCost));
            } else {
                $eoq = 0;
            }

            // Analisis tren penjualan
            $trend = 0;
            if ($weeklySales->count() > 1) {
                $firstWeek = $weeklySales->first()->total;
                $lastWeek = $weeklySales->last()->total;
                $trend = ($lastWeek - $firstWeek) / $weeklySales->count();
            }

            $product->forecast = [
                'weekly_sales' => $weeklySales,
                'avg_weekly_sales' => $avgWeeklySales,
                'std_dev' => $stdDev,
                'lead_time' => $leadTime,
                'safety_stock' => $safetyStock,
                'reorder_point' => $reorderPoint,
                'eoq' => $eoq,
                'trend' => $trend,
                'next_week_forecast' => $avgWeeklySales + $trend
            ];
        }

        if ($request->has('export')) {
            return Excel::download(new StockForecastExport($products), 'forecast-stok.xlsx');
        }

        return view('Admin.stocks.forecast', compact('products', 'categories'));
    }

    public function generateBarcode($id)
    {
        $product = Product::findOrFail($id);
        $barcode = new DNS1D();
        $barcode->setStorPath(storage_path('app/public/barcodes'));
        
        $barcodeImage = $barcode->getBarcodePNG($product->code, 'C128');
        
        return response($barcodeImage)
            ->header('Content-Type', 'image/png');
    }

    public function printBarcode($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.stocks.barcode', compact('product'));
    }

    public function printBulkBarcode(Request $request)
    {
        $request->validate([
            'produk_ids' => 'required|array',
            'produk_ids.*' => 'exists:products,id'
        ]);

        $products = Product::whereIn('id', $request->produk_ids)->get();
        return view('admin.stocks.bulk-barcode', compact('products'));
    }
}
