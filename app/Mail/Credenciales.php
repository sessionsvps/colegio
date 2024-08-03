<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Credenciales extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $password;
    public $esEstudiante;

    public function __construct($email, $password, $esEstudiante)
    {
        $this->email = $email;
        $this->password = $password;
        $this->esEstudiante = $esEstudiante;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'CREDENCIALES DE ACCESO',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.credenciales',
            with: [
                'email' => $this->email,
                'password' => $this->password,
                'esEstudiante' => $this->esEstudiante,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
