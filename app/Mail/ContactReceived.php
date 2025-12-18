<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $messageModel;

    public function __construct(ContactMessage $messageModel)
    {
        $this->messageModel = $messageModel;
    }

    public function build()
    {
        return $this->subject('New contact message — GOFX')
                    ->view('emails.contact_received')
                    ->with(['msg' => $this->messageModel]);
    }
}
