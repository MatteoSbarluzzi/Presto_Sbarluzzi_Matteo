<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function homepage()
    {
        $articles = Article::where('is_accepted', true)->orderBy('created_at', 'desc')->take(6)->get();
        return view('welcome', compact('articles'));
    }

    public function searchArticles(Request $request)
    { 
        //Consente di interrogare il motore di ricerca e recuperare risultati pertinenti in base al termine di ricerca dell'utente.
        $search = $request->input('query');
        $articles = Article::search($search)->where('is_accepted', true)->paginate(10); //massimo 10 articoli per pagina
        return view('article.searched', ['articles' => $articles, 'query' => $search]);
    }
}
