<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeUserRevisor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-user-revisor {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rende un utente revisore';

    /**
     * Execute the console command.
     */
    public function handle()
    {   // prende l'argomento passato da terminale all'esecuzione del comando php artisan app:make-user-revisor. Il where cerca nel database l'email passata come argomento e la prima occorrenza viene salvata nella variabile $user
        $user = User::where('email', $this->argument('email'))->first();
        if (!$user) { //se $user è null, significa che non è stato trovato alcun utente con quell'email
            $this->error('Utente non trovato.');
            return;
        }
        $user->is_revisor = true; //imposta la colonna is_revisor a true
        $user->save();
        $this->info("l'utente {$user->name} è ora revisore");
    }
}
