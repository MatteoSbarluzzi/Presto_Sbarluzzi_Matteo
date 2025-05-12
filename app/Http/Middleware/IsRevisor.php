<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Middleware\IsRevisor;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsRevisor
{
    public function handle(Request $request, Closure $next): Response
    {   
        //Se l'utente è loggato e ha il ruolo di revisore, prosegui
        if (Auth::check() && Auth::user()->is_revisor){
            return $next($request);
        }
        return redirect()->route('homepage')->with('errorMessage', 'Zona riservata ai revisori');
    }
}
