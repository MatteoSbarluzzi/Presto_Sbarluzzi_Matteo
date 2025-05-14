<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
//Questo middleware controlla la sessione dell'utente per trovare una lingua preferita e imposta di conseguenza la lingua dell'applicazione per ogni richiesta. Questo garantirà che gli utenti vedano contenuti e messaggi nella loro lingua preferita in base ai dati della sessione
{
    public function handle(Request $request, Closure $next): Response
    {   
        //Il middleware SetLocaleMiddleware viene utilizzato per impostare la lingua dell'applicazione in base alla lingua selezionata dall'utente
        $localeLanguage = session('locale', 'it');
        App::setLocale($localeLanguage); //Imposta la lingua dell'applicazione in base alla lingua selezionata dall'utente, se non è presente nella sessione, viene utilizzata la lingua predefinita 'it' (italiano)
        return $next($request); // Passa la richiesta al middleware successivo nella catena e raggiunge la rotta o il controller previsti
    }
}
