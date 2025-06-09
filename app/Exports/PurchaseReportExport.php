<?php

namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class PurchaseReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Purchase::with(['supplier', 'details', 'approvedBy', 'rejectedBy', 'receivedBy'])
            ->when($this->request->supplier_id, function($q) {
                return $q->where('supplier_id', $this->request->supplier_id);
            })
            ->when($this->request->status, function($q) {
                return $q->where('status', $this->request->status);
            })
            ->when($this->request->date_start && $this->request->date_end, function($q) {
                return $q->whereBetween('purchase_date', [
                    Carbon::parse($this->request->date_start)->startOfDay(),
                    Carbon::parse($this->request->date_end)->endOfDay()
                ]);
            });

        if (auth()->user()->role === 'admin') {
            $query->where('created_by', auth()->id());
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'No. Pembelian',
            'Pemasok',
            'Status',
            'Total',
            'Dibuat Oleh',
            'Disetujui Oleh',
            'Ditolak Oleh',
            'Diterima Oleh'
        ];
    }

    public function map($purchase): array
    {
        static $rowNumber = 1;
        
        return [
            $rowNumber++,
            Carbon::parse($purchase->purchase_date)->format('d/m/Y'),
            $purchase->invoice_number,
            $purchase->supplier->name,
            $this->getStatusText($purchase->status),
            number_format($purchase->total_amount, 0, ',', '.'),
            $purchase->createdBy->name,
            $purchase->approvedBy ? $purchase->approvedBy->name : '-',
            $purchase->rejectedBy ? $purchase->rejectedBy->name : '-',
            $purchase->receivedBy ? $purchase->receivedBy->name : '-'
        ];
    }

    private function getStatusText($status)
    {
        $statusMap = [
            'draft' => 'Draft',
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'received' => 'Diterima'
        ];

        return $statusMap[$status] ?? $status;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
} 