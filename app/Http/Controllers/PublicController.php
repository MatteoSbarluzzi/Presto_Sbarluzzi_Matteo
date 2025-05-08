<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function homepage()
    {
        $articles = Article::take(6)->orderBy('created_at', 'desc')->get(); //prende e visualizza solo gli ultimi 6 annunci dal più recente restituendo i risultati come collezione con get
        return view('welcome', compact('articles'));
    }
}
