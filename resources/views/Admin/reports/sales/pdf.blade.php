<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            padding: 0;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary {
            margin-bottom: 20px;
        }
        .summary table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary th, .summary td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .summary th {
            background-color: #f5f5f5;
        }
        .chart {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .chart img {
            max-width: 100%;
            height: auto;
        }
        .top-items {
            margin-bottom: 20px;
        }
        .top-items h3 {
            margin: 10px 0;
        }
        .top-items table {
            width: 100%;
            border-collapse: collapse;
        }
        .top-items th, .top-items td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .top-items th {
            background-color: #f5f5f5;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Penjualan</h2>
        <p>Periode: {{ $startDate->format('d/m/Y') }} s/d {{ $endDate->format('d/m/Y') }}</p>
    </div>

    <!-- Ringkasan -->
    <div class="summary">
        <h3>Ringkasan</h3>
        <table>
            <tr>
                <th>Metrik</th>
                <th>Nilai</th>
                <th>Periode Sebelumnya</th>
                <th>Pertumbuhan</th>
            </tr>
            <tr>
                <td>Total Penjualan</td>
                <td>Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($comparison['previous']->total_sales, 0, ',', '.') }}</td>
                <td>{{ number_format($comparison['growth']['sales'], 1) }}%</td>
            </tr>
            <tr>
                <td>Total Profit</td>
                <td>Rp {{ number_format($summary['total_profit'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($comparison['previous']->total_profit, 0, ',', '.') }}</td>
                <td>{{ number_format($comparison['growth']['profit'], 1) }}%</td>
            </tr>
            <tr>
                <td>Total Order</td>
                <td>{{ $summary['total_orders'] }}</td>
                <td>{{ $comparison['previous']->total_orders }}</td>
                <td>{{ number_format($comparison['growth']['orders'], 1) }}%</td>
            </tr>
            <tr>
                <td>Rata-rata Order</td>
                <td>Rp {{ number_format($summary['average_order_value'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($comparison['previous']->total_sales / $comparison['previous']->total_orders, 0, ',', '.') }}</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Profit Margin</td>
                <td>{{ number_format($summary['profit_margin'], 1) }}%</td>
                <td>{{ number_format(($comparison['previous']->total_profit / $comparison['previous']->total_sales) * 100, 1) }}%</td>
                <td>-</td>
            </tr>
        </table>
    </div>

    <!-- Top Products -->
    <div class="top-items">
        <h3>Top 10 Produk</h3>
        <table>
            <tr>
                <th>Kode</th>
                <th>Produk</th>
                <th>Total Quantity</th>
                <th>Total Penjualan</th>
                <th>Total Profit</th>
                <th>Profit Margin</th>
            </tr>
            @foreach($topProducts as $product)
                <tr>
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->total_quantity }}</td>
                    <td>Rp {{ number_format($product->total_sales, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($product->total_profit, 0, ',', '.') }}</td>
                    <td>{{ number_format(($product->total_profit / $product->total_sales) * 100, 1) }}%</td>
                </tr>
            @endforeach
        </table>
    </div>

    <!-- Top Customers -->
    <div class="top-items">
        <h3>Top 10 Pelanggan</h3>
        <table>
            <tr>
                <th>Kode</th>
                <th>Pelanggan</th>
                <th>Total Order</th>
                <th>Total Penjualan</th>
                <th>Total Profit</th>
                <th>Profit Margin</th>
            </tr>
            @foreach($topCustomers as $customer)
                <tr>
                    <td>{{ $customer->code }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->total_orders }}</td>
                    <td>Rp {{ number_format($customer->total_sales, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($customer->total_profit, 0, ',', '.') }}</td>
                    <td>{{ number_format(($customer->total_profit / $customer->total_sales) * 100, 1) }}%</td>
                </tr>
            @endforeach
        </table>
    </div>

    <!-- Top Categories -->
    <div class="top-items">
        <h3>Top 10 Kategori</h3>
        <table>
            <tr>
                <th>Kategori</th>
                <th>Total Quantity</th>
                <th>Total Penjualan</th>
                <th>Total Profit</th>
                <th>Profit Margin</th>
            </tr>
            @foreach($topCategories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->total_quantity }}</td>
                    <td>Rp {{ number_format($category->total_sales, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($category->total_profit, 0, ',', '.') }}</td>
                    <td>{{ number_format(($category->total_profit / $category->total_sales) * 100, 1) }}%</td>
                </tr>
            @endforeach
        </table>
    </div>

    <!-- Profit Analysis -->
    <div class="top-items">
        <h3>Analisis Profit per Kategori</h3>
        <table>
            <tr>
                <th>Kategori</th>
                <th>Total Penjualan</th>
                <th>Total Profit</th>
                <th>Profit Margin</th>
            </tr>
            @foreach($profitAnalysis as $analysis)
                <tr>
                    <td>{{ $analysis->name }}</td>
                    <td>Rp {{ number_format($analysis->total_sales, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($analysis->total_profit, 0, ',', '.') }}</td>
                    <td>{{ number_format($analysis->profit_margin, 1) }}%</td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html> 