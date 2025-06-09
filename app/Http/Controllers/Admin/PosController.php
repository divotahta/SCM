<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionLog;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\StockHistory;

class PosController extends Controller
{
    public function index()
    {
        return view('admin.pos.index');
    }

    public function searchProducts(Request $request)
    {
        $query = $request->get('q');
        
        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('code', 'like', "%{$query}%")
            ->where('stock', '>', 0)
            ->select('id', 'name', 'code', 'price', 'stock')
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    public function getCustomers()
    {
        $customers = Customer::select('id', 'name')->get();
        return response()->json($customers);
    }

    public function processTransaction(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,transfer,qris',
            'payment_amount' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Validasi stok
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['id']);
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok produk {$product->name} tidak mencukupi. Tersedia: {$product->stock}");
                }
            }

            // Create transaction
            $transaction = Transaction::create([
                'customer_id' => $request->customer_id,
                'payment_method' => $request->payment_method,
                'payment_amount' => $request->payment_amount,
                'total' => $request->total,
                'status' => 'completed'
            ]);

            // Create transaction details and update stock
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['id']);
                
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ]);

                // Update stock
                $oldStock = $product->stock;
                $product->decrement('stock', $item['quantity']);
                $newStock = $product->stock;

                // Log perubahan stok
                Log::info('Stock updated', [
                    'product_id' => $product->id,
                    'old_stock' => $oldStock,
                    'new_stock' => $newStock,
                    'quantity' => $item['quantity']
                ]);
            }

            // Log transaksi
            TransactionLog::create([
                'transaction_id' => $transaction->id,
                'user_id' => Auth::id(),
                'action' => 'create',
                'description' => 'Transaksi baru dibuat',
                'new_data' => [
                    'invoice_number' => $transaction->invoice_number,
                    'total' => $transaction->total,
                    'payment_method' => $transaction->payment_method
                ]
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'transaction_id' => $transaction->id,
                'invoice_number' => $transaction->invoice_number
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction failed', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function printReceipt($id)
    {
        $transaction = Transaction::with(['customer', 'details.product'])
            ->findOrFail($id);

        return view('admin.pos.receipt', compact('transaction'));
    }

    public function voidTransaction($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $transaction->void();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibatalkan'
            ]);
        } catch (\Exception $e) {
            Log::error('Void transaction failed', [
                'transaction_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getTransactionStatus($id)
    {
        $transaction = Transaction::findOrFail($id);
        return response()->json([
            'status' => $transaction->status,
            'invoice_number' => $transaction->invoice_number
        ]);
    }

    protected function updateStock($product, $quantity, $type = 'out', $reference = null)
    {
        DB::transaction(function() use ($product, $quantity, $type, $reference) {
            // Update stock
            $product->stock += ($type == 'in' ? $quantity : -$quantity);
            $product->save();

            // Create history
            StockHistory::create([
                'product_id' => $product->id,
                'type' => $type,
                'quantity' => $quantity,
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference ? $reference->id : null,
                'notes' => $reference ? 'Transaksi ' . ($type == 'in' ? 'pembelian' : 'penjualan') : 'Penyesuaian manual',
                'created_by' => Auth::id()
            ]);
        });
    }
} 