<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function collection()
    {
        return $this->transactions;
    }

    public function headings(): array
    {
        return [
            'Kode Transaksi',
            'Tanggal',
            'Pelanggan',
            'Total Harga',
            'Total Bayar',
            'Kembalian',
            'Metode Pembayaran',
            'Status',
            'Catatan'
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->kode_transaksi,
            $transaction->created_at->format('d/m/Y H:i'),
            $transaction->customer->name,
            number_format($transaction->total_harga, 0, ',', '.'),
            number_format($transaction->total_bayar, 0, ',', '.'),
            number_format($transaction->total_kembali, 0, ',', '.'),
            ucfirst($transaction->metode_pembayaran),
            ucfirst($transaction->status),
            $transaction->catatan
        ];
    }
} 