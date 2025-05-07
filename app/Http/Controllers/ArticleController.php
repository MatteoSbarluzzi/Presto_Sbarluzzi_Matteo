<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ArticleController extends Controller implements HasMiddleware
{
    // Solo gli utenti loggati possono accedere al metodo 'create'
    public static function middleware(): array
    {
        return [
            new Middleware('auth', only: ['create']),
        ];
    }

    // Mostra la vista con il componente Livewire per creare un articolo
    public function create()
{
    return view('article.create');
}

}
