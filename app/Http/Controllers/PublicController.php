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
    public function homepage()
    {
        $articles = Article::where('is_accepted', true)->orderBy('created_at', 'desc')->take(8)->get();
        $reviews = Review::latest()->take(5)->with('user')->get(); // carichiamo anche le ultime recensioni per la welcome
        return view('welcome', compact('articles', 'reviews'));
    }

    public function searchArticles(Request $request)
    { 
        //Consente di interrogare il motore di ricerca e recuperare risultati pertinenti in base al termine di ricerca dell'utente.
        $search = $request->input('query');
        $articles = Article::search($search)->where('is_accepted', true)->paginate(10); //massimo 10 articoli per pagina
        return view('article.searched', ['articles' => $articles, 'query' => $search]);
    }

    public function setLanguage($lang)
    {
        session()->put('locale', $lang); //memorizza la lingua selezionata nella sessione
        return redirect()->back();
    }

    public function shippingAndReturns()
    {
        $categories = Category::all();
        return view('shipping-and-returns', compact('categories'));
    }

    public function reviews()
    {
        $reviews = Review::latest()->take(5)->with('user')->get();
 // carichiamo le ultime 5 recensioni
        return view('reviews.index', compact('reviews'));
    }

    public function subscribeNewsletter(Request $request)
    {
        $email = $request->input('email');

        // Invio all’amministratore
        Mail::to('admin@presto.it')->send(new NewsletterSubscribed($email));

        // Invio all’utente
        Mail::to($email)->send(new NewsletterWelcome($email));

        return redirect()
            ->route('homepage')
            ->with('message', __('ui.newsletter_thank_you'));
    }
}
