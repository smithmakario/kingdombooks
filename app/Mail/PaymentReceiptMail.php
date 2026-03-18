<?php

namespace App\Mail;

use App\Models\PaymentReceipt;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PaymentReceipt $receipt,
        public string $downloadUrl,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Kingdom Books Payment Receipt',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-receipt',
        );
    }
}
