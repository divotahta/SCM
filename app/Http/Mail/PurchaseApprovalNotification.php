<?php

namespace App\Mail;

use App\Models\Purchase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseApprovalNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $purchase;
    public $status;

    public function __construct(Purchase $purchase, string $status)
    {
        $this->purchase = $purchase;
        $this->status = $status;
    }

    public function build()
    {
        $subject = $this->status === 'approved' 
            ? 'Pembelian #' . $this->purchase->invoice_number . ' Telah Disetujui'
            : 'Pembelian #' . $this->purchase->invoice_number . ' Ditolak';

        return $this->subject($subject)
            ->markdown('emails.purchase-approval');
    }
} 