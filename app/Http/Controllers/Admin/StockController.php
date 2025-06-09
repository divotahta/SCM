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
        $query = Product::with(['category', 'unit']);

        // Filter berdasarkan pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan kategori
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter berdasarkan status stok
        if ($request->has('stock_status')) {
            switch ($request->stock_status) {
                case 'low':
                    $query->where('stock', '<=', DB::raw('min_stock'));
                    break;
                case 'out':
                    $query->where('stock', '<=', 0);
                    break;
                case 'available':
                    $query->where('stock', '>', 0);
                    break;
            }
        }

        $products = $query->paginate(10);
        $categories = Category::all();

        return view('admin.stocks.index', compact('products', 'categories'));
    }

    public function history(Request $request)
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

        // Filter berdasarkan tipe
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $histories = $query->latest()->paginate(20);

        return view('admin.stocks.history', compact('histories'));
    }

    public function adjust(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric',
            'type' => 'required|in:addition,reduction',
            'reason' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);
            $oldStock = $product->stock;
            
            // Update stok produk
            if ($request->type == 'addition') {
                $product->stock += $request->quantity;
            } else {
                if ($product->stock < $request->quantity) {
                    throw new \Exception('Stok tidak mencukupi');
                }
                $product->stock -= $request->quantity;
            }
            $product->save();

            // Catat history
            StockHistory::create([
                'product_id' => $product->id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'old_stock' => $oldStock,
                'new_stock' => $product->stock,
                'description' => $request->reason,
                'user_id' => Auth::id()
            ]);

            // Catat adjustment
            StockAdjustment::create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'type' => $request->type,
                'reason' => $request->reason,
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
        $query = Product::with(['category', 'unit']);

        // Filter berdasarkan kategori
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->get();
        $categories = Category::all();

        // Hitung forecast untuk setiap produk
        foreach ($products as $product) {
            // Hitung rata-rata penjualan per bulan
            $monthlySales = StockHistory::where('product_id', $product->id)
                ->where('type', 'reduction')
                ->where('created_at', '>=', now()->subMonths(3))
                ->avg('quantity');

            // Hitung lead time (waktu tunggu) dalam hari
            $leadTime = 7; // Contoh: 7 hari

            // Hitung safety stock (20% dari rata-rata penjualan bulanan)
            $safetyStock = $monthlySales * 0.2;

            // Hitung reorder point
            if ($monthlySales > 0) {
                $reorderPoint = ($monthlySales / 30 * $leadTime) + $safetyStock;
            } else {
                $reorderPoint = $safetyStock; // Jika tidak ada penjualan, gunakan safety stock saja
            }

            // Hitung economic order quantity (EOQ)
            $orderCost = 100000; // Biaya pemesanan
            $holdingCost = 0.2; // Biaya penyimpanan (20% dari harga)
            $annualDemand = $monthlySales * 12;
            
            // Cek apakah purchase_price dan holdingCost tidak nol
            if ($product->purchase_price > 0 && $holdingCost > 0) {
                $eoq = sqrt((2 * $annualDemand * $orderCost) / ($product->purchase_price * $holdingCost));
            } else {
                $eoq = 0; // Set default value jika terjadi pembagian dengan nol
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

        return view('admin.stocks.forecast', compact('products', 'categories'));
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
