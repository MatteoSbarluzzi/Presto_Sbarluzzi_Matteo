<?php

namespace App\Mail;

use App\Models\User;
use App\Mail\BecomeRevisor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class BecomeRevisor extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // Utente che ha fatto richiesta di diventare revisore

    public function __construct(User $user)
    {
        $this->user = $user;
    }


    public function envelope(): Envelope 
    // Impostare le informazioni sull'intestazione dell'email
    {
        return new Envelope(
            subject: "Rendi revisore l'utente" . $this->user->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {   // La vista che vogliamo spedire all'admin
        return new Content(
            view: 'mail.become-revisor',
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
