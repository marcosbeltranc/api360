<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ServerAccessRequestMail extends Mailable
{
    public $requestData;

    public function __construct($requestData)
    {
        $this->requestData = $requestData;
    }

    public function build()
    {
        return $this->subject("Solicitud de acceso [GAIA-{$this->requestData->id}]")
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->replyTo(
                $this->requestData->user->email,
                $this->requestData->user->name
            )
            ->view('emails.server_access');
    }
}
