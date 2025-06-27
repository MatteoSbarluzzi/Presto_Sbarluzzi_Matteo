<?php

namespace App\Mail;

use Illuminate\Bus\Queueable; 
use Illuminate\Contracts\Queue\ShouldQueue; 
use Illuminate\Mail\Mailable; 
use Illuminate\Mail\Mailables\Content; 
use Illuminate\Mail\Mailables\Envelope; 
use Illuminate\Queue\SerializesModels; 

class NewsletterWelcome extends Mailable
{
    use Queueable, SerializesModels; 

    public $email; 

    // Costruttore: riceve l'email come parametro e la assegna alla proprietà $email
    public function __construct($email)
    {
        $this->email = $email;
    }

    // Metodo build: costruisce la mail, definendone soggetto e vista da utilizzare
    public function build()
    {
        return $this->subject(__('ui.newsletter_subject_user')) 
                    ->view('mail.newsletter-welcome'); 
    }
}
