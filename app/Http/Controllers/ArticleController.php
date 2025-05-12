<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
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

    // Mostra l'elenco degli articoli (paginati, dal più recente)
    public function index()
    {
        $articles = Article::where('is_accepted', true)->orderBy('created_at', 'desc')->paginate(10);
        return view('article.index', compact('articles'));
    }

    // Mostra la vista con il dettaglio di un singolo articolo
    public function show(Article $article)
    {
        return view('article.show', compact('article'));
    }

    // Mostra la vista con tutti gli articoli di una specifica categoria
    public function byCategory(Category $category)
    {
        $articles = $category->articles->where('is_accepted', true);
        return view('article.byCategory', compact('articles', 'category'));
    }

    // Mostra la vista con il componente Livewire per creare un articolo
    public function create()
    {
        return view('article.create');
    }
}
