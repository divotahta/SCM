<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderDetail;
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
        $query = Order::with(['customer', 'details.product'])
            ->when($request->search, function($q) use ($request) {
                $q->where('invoice_number', 'like', "%{$request->search}%")
                    ->orWhereHas('customer', function($q) use ($request) {
                        $q->where('name', 'like', "%{$request->search}%");
                    });
            })
            ->when($request->status, function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->date_from, function($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->date_to, function($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            });

        $orders = $query->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function pending(Request $request)
    {
        $query = Order::with(['customer', 'details.product'])
            ->whereIn('status', ['pending', 'processing'])
            ->when($request->search, function($q) use ($request) {
                $q->where('invoice_number', 'like', "%{$request->search}%")
                    ->orWhereHas('customer', function($q) use ($request) {
                        $q->where('name', 'like', "%{$request->search}%");
                    });
            })
            ->when($request->status, function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->date_from, function($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->date_to, function($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            });

        $orders = $query->latest()->paginate(10);
        $pendingCount = Order::where('status', 'pending')->count();
        $processingCount = Order::where('status', 'processing')->count();

        return view('admin.orders.pending', compact('orders', 'pendingCount', 'processingCount'));
    }

    public function completed(Request $request)
    {
        $query = Order::with(['customer', 'details.product'])
            ->where('status', 'completed')
            ->when($request->search, function($q) use ($request) {
                $q->where('invoice_number', 'like', "%{$request->search}%")
                    ->orWhereHas('customer', function($q) use ($request) {
                        $q->where('name', 'like', "%{$request->search}%");
                    });
            })
            ->when($request->date_from, function($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->date_to, function($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            });

        $orders = $query->latest()->paginate(10);
        $completedCount = Order::where('status', 'completed')->count();

        return view('admin.orders.completed', compact('orders', 'completedCount'));
    }

    public function unpaid(Request $request)
    {
        $query = Order::with(['customer', 'details.product'])
            ->where('payment_status', '!=', 'paid')
            ->when($request->search, function($q) use ($request) {
                $q->where('invoice_number', 'like', "%{$request->search}%")
                    ->orWhereHas('customer', function($q) use ($request) {
                        $q->where('name', 'like', "%{$request->search}%");
                    });
            })
            ->when($request->payment_status, function($q) use ($request) {
                $q->where('payment_status', $request->payment_status);
            })
            ->when($request->date_from, function($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->date_to, function($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            });

        $orders = $query->latest()->paginate(10);
        $unpaidCount = Order::where('payment_status', '!=', 'paid')->count();

        return view('admin.orders.unpaid', compact('orders', 'unpaidCount'));
    }

    public function getOrderCounts()
    {
        return response()->json([
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'unpaid' => Order::where('payment_status', '!=', 'paid')->count()
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'details.product', 'logs' => function($query) {
            $query->where('action', 'like', '%status%')->latest();
        }]);
        
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load(['customer', 'details.product']);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        try {
            DB::beginTransaction();

            // Update status jika ada
            if ($request->has('status') && $request->status !== $order->status) {
                $oldStatus = $order->status;
                $order->status = $request->status;
                $order->save();

                // Log perubahan status
                TransactionLog::create([
                    'transaction_id' => $order->id,
                    'transaction_type' => 'order',
                    'action' => 'update_status',
                    'description' => "Status pesanan diubah dari {$oldStatus} menjadi {$request->status}",
                    'old_data' => ['status' => $oldStatus],
                    'new_data' => ['status' => $request->status],
                    'user_id' => Auth::id()
                ]);
            }

            // Update detail pesanan jika ada
            if ($request->has('details')) {
                $details = json_decode($request->details, true);
                $total = 0;

                foreach ($details as $detail) {
                    $orderDetail = OrderDetail::find($detail['id']);
                    if ($orderDetail && $orderDetail->order_id === $order->id) {
                        $oldQuantity = $orderDetail->quantity;
                        $orderDetail->quantity = $detail['quantity'];
                        $orderDetail->subtotal = $orderDetail->price * $detail['quantity'];
                        $orderDetail->save();

                        $total += $orderDetail->subtotal;

                        // Log perubahan quantity
                        if ($oldQuantity != $detail['quantity']) {
                            TransactionLog::create([
                                'transaction_id' => $order->id,
                                'transaction_type' => 'order',
                                'action' => 'update_quantity',
                                'description' => "Quantity produk {$orderDetail->product->name} diubah dari {$oldQuantity} menjadi {$detail['quantity']}",
                                'old_data' => ['quantity' => $oldQuantity],
                                'new_data' => ['quantity' => $detail['quantity']],
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

            // Log penghapusan pesanan
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
        $pdf = PDF::loadView('admin.orders.pdf', compact('orders'));
        return $pdf->download('orders.pdf');
    }
} 