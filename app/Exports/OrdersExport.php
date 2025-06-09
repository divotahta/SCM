<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'No. Faktur',
            'Tanggal',
            'Pelanggan',
            'Total',
            'Metode Pembayaran',
            'Status',
            'Jumlah Item'
        ];
    }

    public function map($order): array
    {
        return [
            $order->invoice_number,
            $order->created_at->format('d/m/Y H:i'),
            $order->customer->name,
            number_format($order->total, 0, ',', '.'),
            ucfirst($order->payment_method),
            ucfirst($order->status),
            $order->details->sum('quantity')
        ];
    }
} 