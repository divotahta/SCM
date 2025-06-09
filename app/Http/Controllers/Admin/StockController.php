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
            'quantity' => 'required|integer',
            'type' => 'required|in:addition,reduction',
            'reason' => 'required|string|max:255'
        ]);

        DB::beginTransaction();
        try {
            $oldStock = $product->stok;
            $quantity = $request->quantity;
            
            if ($request->type === 'addition') {
                $newStock = $oldStock + $quantity;
            } else {
                if ($oldStock < $quantity) {
                    throw new \Exception('Stok tidak mencukupi untuk pengurangan');
                }
                $newStock = $oldStock - $quantity;
            }

            // Update stok produk
            $product->update(['stok' => $newStock]);

            // Catat history
            StockHistory::create([
                'product_id' => $product->id,
                'type' => $request->type,
                'quantity' => $quantity,
                'old_stock' => $oldStock,
                'new_stock' => $newStock,
                'description' => $request->reason,
                'user_id' => Auth::id()
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Stok berhasil disesuaikan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function report(Request $request)
    {
        $query = StockHistory::with(['product', 'user']);

        // Filter berdasarkan tanggal
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        // Filter berdasarkan produk
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
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
            // Hitung rata-rata penjualan bulanan
            $monthlySales = $product->orderDetails()
                ->whereMonth('created_at', now()->month)
                ->sum('jumlah');

            // Hitung lead time (dalam hari)
            $leadTime = 7; // Contoh: 7 hari

            // Hitung safety stock
            $safetyStock = $monthlySales * 0.2; // 20% dari penjualan bulanan

            // Hitung reorder point
            $reorderPoint = ($monthlySales / 30 * $leadTime) + $safetyStock;

            // Hitung economic order quantity (EOQ)
            $orderCost = 100000; // Biaya pemesanan
            $holdingCost = 0.2; // Biaya penyimpanan (20% dari harga)
            $annualDemand = $monthlySales * 12;
            
            if ($product->harga_beli > 0 && $holdingCost > 0) {
                $eoq = sqrt((2 * $annualDemand * $orderCost) / ($product->harga_beli * $holdingCost));
            } else {
                $eoq = 0;
            }

            $product->forecast = [
                'monthly_sales' => $monthlySales,
                'lead_time' => $leadTime,
                'safety_stock' => $safetyStock,
                'reorder_point' => $reorderPoint,
                'eoq' => $eoq
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
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        $products = Product::whereIn('id', $request->product_ids)->get();
        return view('admin.stocks.bulk-barcode', compact('products'));
    }
}
