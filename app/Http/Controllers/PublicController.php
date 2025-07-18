<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Mail\NewsletterSubscribed;
use App\Mail\NewsletterWelcome;
use Illuminate\Support\Facades\Mail;

class PublicController extends Controller
{
    // Homepage con ultimi articoli e recensioni
    public function homepage()
    {
        $articles = Article::where('is_accepted', true)->orderBy('created_at', 'desc')->take(8)->get();
        $reviews = Review::latest()->take(5)->with('user')->get();
        return view('welcome', compact('articles', 'reviews'));
    }

    // Ricerca articoli tramite parola chiave
    public function searchArticles(Request $request)
    {
        $search = $request->input('query');

        $query = Article::where('is_accepted', true)
            ->where('title', 'like', '%' . $search . '%');

        $maxPrice = $query->max('price') ?? 0;
        $articles = $query->paginate(12);

        $categories = Category::all();

        return view('article.searched', [
            'articles' => $articles,
            'query' => $search,
            'categories' => $categories,
            'maxPrice' => $maxPrice,
        ]);
    }

    // Cambia la lingua dell'interfaccia utente
    public function setLanguage($lang)
    {
        session()->put('locale', $lang);
        return redirect()->back();
    }

    // Pagina Spedizioni e Resi
    public function shippingAndReturns()
    {
        $categories = Category::all();
        return view('shipping-and-returns', compact('categories'));
    }

    // Pagina delle recensioni utenti
    public function reviews()
    {
        $reviews = Review::latest()->take(5)->with('user')->get();
        return view('reviews.index', compact('reviews'));
    }

    // Iscrizione alla newsletter (invia 2 email)
    public function subscribeNewsletter(Request $request)
    {
        $email = $request->input('email');

        Mail::to('admin@presto.it')->send(new NewsletterSubscribed($email));
        Mail::to($email)->send(new NewsletterWelcome($email));

        return redirect()
            ->route('homepage')
            ->with('message', 'newsletter_thank_you');
    }
}
