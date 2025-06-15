<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EoqCalculatorController extends Controller
{
    public function index()
    {
        return view('Admin.eoq.eoq-calculator');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_unit' => 'required|string|max:50',
            'purchase_price' => 'required|numeric|min:0',
            'demand' => 'required|numeric|min:0',
            'order_cost' => 'required|numeric|min:0',
            'holding_cost_percentage' => 'required|numeric|min:0|max:100',
            'period' => 'required|in:weekly,monthly,yearly'
        ]);

        // Konversi demand ke tahunan
        $annualDemand = match($request->period) {
            'weekly' => $request->demand * 52,
            'monthly' => $request->demand * 12,
            'yearly' => $request->demand
        };

        // Hitung holding cost per unit
        $holdingCostPerUnit = ($request->purchase_price * $request->holding_cost_percentage) / 100;

        // Hitung EOQ
        $eoq = sqrt((2 * $annualDemand * $request->order_cost) / $holdingCostPerUnit);

        // Hitung jumlah order per tahun
        $ordersPerYear = $annualDemand / $eoq;

        // Hitung waktu antar order (dalam hari)
        $timeBetweenOrders = 365 / $ordersPerYear;

        // Hitung total cost
        $orderingCost = $ordersPerYear * $request->order_cost;
        $holdingCost = ($eoq / 2) * $holdingCostPerUnit;
        $totalCost = $orderingCost + $holdingCost;

        return response()->json([
            'product' => [
                'nama_produk' => $request->product_name,
                'unit' => ['nama_satuan' => $request->product_unit]
            ],
            'eoq' => round($eoq, 2),
            'orders_per_year' => round($ordersPerYear, 2),
            'time_between_orders' => round($timeBetweenOrders, 2),
            'total_cost' => round($totalCost, 2),
            'ordering_cost' => round($orderingCost, 2),
            'holding_cost' => round($holdingCost, 2),
            'annual_demand' => round($annualDemand, 2),
            'holding_cost_per_unit' => round($holdingCostPerUnit, 2)
        ]);
    }
} 