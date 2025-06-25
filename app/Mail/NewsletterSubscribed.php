<?php

namespace App\Mail;

use Illuminate\Bus\Queueable; 
use Illuminate\Mail\Mailable; 
use App\Mail\NewsletterSubscribed; 
use Illuminate\Queue\SerializesModels; 

class NewsletterSubscribed extends Mailable
{
    use Queueable, SerializesModels; 

    public $email; 

    // Costruttore: riceve l'email dell'utente e la assegna alla proprietà pubblica
    public function __construct($email)
    {
        $this->email = $email;
    }

    // Metodo build: definisce soggetto e contenuto della mail
    public function build()
    {
        return $this->subject(__('ui.new_newsletter_subscription')) 
                    ->view('mail.newsletter-subscribed'); 
    }
}
