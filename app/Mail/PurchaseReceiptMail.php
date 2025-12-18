<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $receipt;
    public $pdfPath;
    public $isAdmin;

    public function __construct(array $receipt, string $pdfPath = null, bool $isAdmin = false)
    {
        $this->receipt = $receipt;
        $this->pdfPath = $pdfPath;
        $this->isAdmin = $isAdmin;
    }

    public function build()
    {
        $subject = $this->isAdmin ? "New Course Purchase — {$this->receipt['course']}" : "Your GOFX Purchase Receipt — {$this->receipt['course']}";

        $email = $this->subject($subject)
            ->view('emails.purchase_receipt')
            ->with(['receipt' => $this->receipt]);

        if ($this->pdfPath && file_exists($this->pdfPath)) {
            $email->attach($this->pdfPath, [
                'as' => basename($this->pdfPath),
                'mime' => 'application/pdf',
            ]);
        }

        return $email;
    }
}
