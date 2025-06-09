@component('mail::message')
# {{ $status === 'approved' ? 'Pembelian Disetujui' : 'Pembelian Ditolak' }}

Pembelian dengan nomor **#{{ $purchase->invoice_number }}** telah {{ $status === 'approved' ? 'disetujui' : 'ditolak' }}.

**Detail Pembelian:**
- Tanggal: {{ $purchase->purchase_date->format('d/m/Y') }}
- Pemasok: {{ $purchase->supplier->name }}
- Total: Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}

@if($status === 'rejected')
**Alasan Penolakan:**
{{ $purchase->rejection_reason }}
@endif

@if($status === 'approved')
Pembelian ini telah disetujui dan dapat diproses lebih lanjut.
@else
Silakan periksa alasan penolakan dan lakukan perbaikan yang diperlukan.
@endif

@component('mail::button', ['url' => route('admin.purchases.show', $purchase)])
Lihat Detail Pembelian
@endcomponent

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent 