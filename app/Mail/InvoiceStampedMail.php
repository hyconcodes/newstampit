<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceStampedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Invoice Has Been Stamped - ' . ucfirst($this->invoice->fee_type) . ' Payment Verified',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice-stamped',
            with: [
                'invoice' => $this->invoice,
                'studentName' => $this->invoice->user->name,
                'feeType' => ucfirst(str_replace('_', ' ', $this->invoice->fee_type)),
            ]
        );
    }

    public function attachments(): array
    {
        $attachments = [];
        
        // Attach the stamped invoice if it exists
        if ($this->invoice->stamped_file) {
            $stampedPath = storage_path('app/public/' . $this->invoice->stamped_file);
            if (file_exists($stampedPath)) {
                // Create a descriptive filename for the attachment
                $feeType = ucfirst(str_replace('_', ' ', $this->invoice->fee_type));
                $fileName = "Stamped_{$feeType}_Receipt_{$this->invoice->rrr}.pdf";
                
                $attachments[] = Attachment::fromPath($stampedPath)
                    ->as($fileName)
                    ->withMime('application/pdf');
            }
        }
        
        return $attachments;
    }
}
