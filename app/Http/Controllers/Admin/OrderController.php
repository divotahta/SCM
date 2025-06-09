<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Exports\OrdersExport;
use App\Models\TransactionLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['customer', 'details.product'])
            ->when($request->search, function($q) use ($request) {
                $q->where('nomor_faktur', 'like', "%{$request->search}%")
                  ->orWhereHas('customer', function($q) use ($request) {
                      $q->where('nama', 'like', "%{$request->search}%");
                  });
            })
            ->when($request->status_pesanan, function($q) use ($request) {
                $q->where('status_pesanan', $request->status_pesanan);
            })
            ->when($request->date_from, function($q) use ($request) {
                $q->whereDate('tanggal_pesanan', '>=', $request->date_from);
            })
            ->when($request->date_to, function($q) use ($request) {
                $q->whereDate('tanggal_pesanan', '<=', $request->date_to);
            })
            ->latest()
            ->paginate(10);

        $customers = Customer::all();

        return view('Admin.orders.index', compact('orders', 'customers'));
    }

    public function pending(Request $request)
    {
        $orders = Order::with(['customer', 'details.product'])
            ->whereIn('status_pesanan', ['pending', 'processing'])
            ->when($request->search, function($q) use ($request) {
                $q->where('nomor_faktur', 'like', "%{$request->search}%")
                  ->orWhereHas('customer', function($q) use ($request) {
                      $q->where('nama', 'like', "%{$request->search}%");
                  });
            })
            ->when($request->status_pesanan, function($q) use ($request) {
                $q->where('status_pesanan', $request->status_pesanan);
            })
            ->when($request->date_from, function($q) use ($request) {
                $q->whereDate('tanggal_pesanan', '>=', $request->date_from);
            })
            ->when($request->date_to, function($q) use ($request) {
                $q->whereDate('tanggal_pesanan', '<=', $request->date_to);
            })
            ->latest()
            ->paginate(10);

        $pendingCount = Order::where('status_pesanan', 'pending')->count();
        $processingCount = Order::where('status_pesanan', 'processing')->count();

        return view('Admin.orders.pending', compact('orders', 'pendingCount', 'processingCount'));
    }

    public function completed(Request $request)
    {
        $orders = Order::with(['customer', 'details.product'])
            ->where('status_pesanan', 'completed')
            ->when($request->search, function($q) use ($request) {
                $q->where('nomor_faktur', 'like', "%{$request->search}%")
                  ->orWhereHas('customer', function($q) use ($request) {
                      $q->where('nama', 'like', "%{$request->search}%");
                  });
            })
            ->when($request->date_from, function($q) use ($request) {
                $q->whereDate('tanggal_pesanan', '>=', $request->date_from);
            })
            ->when($request->date_to, function($q) use ($request) {
                $q->whereDate('tanggal_pesanan', '<=', $request->date_to);
            })
            ->latest()
            ->paginate(10);

        $completedCount = Order::where('status_pesanan', 'completed')->count();

        return view('Admin.orders.completed', compact('orders', 'completedCount'));
    }

    public function unpaid(Request $request)
    {
        $orders = Order::with(['customer', 'details.product'])
            ->where('jenis_pembayaran', 'credit')
            ->where('bayar', '<', DB::raw('total'))
            ->when($request->search, function($q) use ($request) {
                $q->where('nomor_faktur', 'like', "%{$request->search}%")
                  ->orWhereHas('customer', function($q) use ($request) {
                      $q->where('nama', 'like', "%{$request->search}%");
                  });
            })
            ->when($request->date_from, function($q) use ($request) {
                $q->whereDate('tanggal_pesanan', '>=', $request->date_from);
            })
            ->when($request->date_to, function($q) use ($request) {
                $q->whereDate('tanggal_pesanan', '<=', $request->date_to);
            })
            ->latest()
            ->paginate(10);

        $unpaidCount = Order::where('jenis_pembayaran', 'credit')
            ->where('bayar', '<', DB::raw('total'))
            ->count();

        return view('Admin.orders.unpaid', compact('orders', 'unpaidCount'));
    }

    public function getOrderCounts()
    {
        $counts = [
            'total' => Order::count(),
            'pending' => Order::where('status_pesanan', 'pending')->count(),
            'processing' => Order::where('status_pesanan', 'processing')->count(),
            'completed' => Order::where('status_pesanan', 'completed')->count(),
            'cancelled' => Order::where('status_pesanan', 'cancelled')->count(),
        ];

        return response()->json($counts);
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'details.product', 'logs' => function($query) {
            $query->where('action', 'like', '%status%')->latest();
        }]);
        
        return view('Admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load(['customer', 'details.product']);
        return view('Admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        try {
            DB::beginTransaction();

            if ($request->has('status_pesanan') && $request->status_pesanan !== $order->status_pesanan) {
                $oldStatus = $order->status_pesanan;
                $order->status_pesanan = $request->status_pesanan;
                $order->save();

                TransactionLog::create([
                    'transaction_id' => $order->id,
                    'transaction_type' => 'order',
                    'action' => 'update_status',
                    'description' => "Status pesanan diubah dari {$oldStatus} menjadi {$request->status_pesanan}",
                    'old_data' => ['status_pesanan' => $oldStatus],
                    'new_data' => ['status_pesanan' => $request->status_pesanan],
                    'user_id' => Auth::id()
                ]);
            }

            if ($request->has('details')) {
                $details = json_decode($request->details, true);
                $total = 0;

                foreach ($details as $detail) {
                    $orderDetail = OrderDetail::find($detail['id']);
                    if ($orderDetail && $orderDetail->pesanan_id === $order->id) {
                        $oldQuantity = $orderDetail->jumlah;
                        $orderDetail->jumlah = $detail['quantity'];
                        $orderDetail->total = $orderDetail->harga_satuan * $detail['quantity'];
                        $orderDetail->save();

                        $total += $orderDetail->total;

                        if ($oldQuantity != $detail['quantity']) {
                            TransactionLog::create([
                                'transaction_id' => $order->id,
                                'transaction_type' => 'order',
                                'action' => 'update_quantity',
                                'description' => "Jumlah produk {$orderDetail->product->nama_produk} diubah dari {$oldQuantity} menjadi {$detail['quantity']}",
                                'old_data' => ['jumlah' => $oldQuantity],
                                'new_data' => ['jumlah' => $detail['quantity']],
                                'user_id' => Auth::id()
                            ]);
                        }
                    }
                }

                $order->total = $total;
                $order->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil diperbarui',
                'redirect' => route('admin.orders.show', $order->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating order: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui pesanan'
            ], 500);
        }
    }

    public function destroy(Order $order)
    {
        try {
            DB::beginTransaction();

            TransactionLog::create([
                'transaction_id' => $order->id,
                'transaction_type' => 'order',
                'action' => 'delete',
                'description' => "Pesanan dihapus",
                'old_data' => $order->toArray(),
                'new_data' => null,
                'user_id' => Auth::id()
            ]);

            $order->details()->delete();
            $order->delete();

            DB::commit();

            return redirect()->route('admin.orders.index')
                ->with('success', 'Pesanan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting order: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus pesanan');
        }
    }

    public function exportExcel()
    {
        $orders = Order::with(['customer', 'details.product'])->get();
        return Excel::download(new OrdersExport($orders), 'orders.xlsx');
    }

    public function exportPdf()
    {
        $orders = Order::with(['customer', 'details.product'])->get();
        $pdf = PDF::loadView('Admin.orders.pdf', compact('orders'));
        return $pdf->download('orders.pdf');
    }

    public function create()
    {
        $customers = Customer::orderBy('nama')->get();
        $products = Product::where('stok', '>', 0)
            ->orderBy('nama_produk')
            ->get();

        return view('Admin.orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'tanggal_pesanan' => 'required|date',
            'status_pesanan' => 'required|in:pending,processing,completed,cancelled',
            'metode_pembayaran' => 'required|in:cash,transfer,qris',
            'status_pembayaran' => 'required|in:paid,unpaid,partial',
            'catatan' => 'nullable|string',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Generate nomor faktur
            $lastOrder = Order::latest()->first();
            $lastNumber = $lastOrder ? intval(substr($lastOrder->nomor_faktur, 3)) : 0;
            $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
            $nomorFaktur = 'INV' . $newNumber;

            // Hitung total
            $total = 0;
            foreach ($request->products as $item) {
                $total += $item['quantity'] * $item['price'];
            }

            // Buat pesanan
            $order = Order::create([
                'customer_id' => $request->customer_id,
                'nomor_faktur' => $nomorFaktur,
                'tanggal_pesanan' => $request->tanggal_pesanan,
                'total' => $total,
                'status_pesanan' => $request->status_pesanan,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pembayaran' => $request->status_pembayaran,
                'catatan' => $request->catatan,
                'created_by' => Auth::user()->id,
            ]);

            // Simpan detail pesanan
            foreach ($request->products as $item) {
                $order->details()->create([
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);

                // Update stok produk
                $product = Product::find($item['id']);
                $product->stok -= $item['quantity'];
                $product->save();
            }

            DB::commit();

            return redirect()
                ->route('admin.orders.show', $order->id)
                ->with('success', 'Pesanan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
} 