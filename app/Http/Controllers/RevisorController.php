<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use App\Mail\BecomeRevisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;

class RevisorController extends Controller
{
    public function index()
    {
        $article_to_check = Article::where('is_accepted', null)->first();
        return view('revisor.index', compact('article_to_check')); //salviamo il primo articolo che corrisponde alla condizione avere nella colonna null e passiamo questo dato alla vista
    }

    public function accept(Article $article)
    {
        $article->isAccepted(true);
        session(['last_reviewed_article_id' => $article->id]);
        return redirect()
            ->route('revisor.index') //redirect alla rotta corretta dopo l'approvazione
            ->with('message', "Hai accettato l'articolo {$article->title}");
    }

    public function reject(Article $article)
    {
        $article->isAccepted(false);
        session(['last_reviewed_article_id' => $article->id]);
        return redirect()
            ->back()
            ->with('message', "Hai rifiutato l'articolo {$article->title}");
    }

    public function undoLastReview()
    {
        $articleId = session('last_reviewed_article_id');
        if ($articleId) {
            $article = Article::find($articleId);
            if ($article) {
                $article->is_accepted = null;
                $article->save();
                session()->forget('last_reviewed_article_id');
                return redirect()->route('revisor.index')->with('message', "Hai annullato l'ultima azione su {$article->title}");
            }
        }

        return redirect()->route('revisor.index')->with('errorMessage', 'Nessuna azione da annullare');
    }

    public function becomeRevisor()
    {
        Mail::to('admin@presto.it')->send(new BecomeRevisor(Auth::user()));
        return redirect()
            ->route('homepage')
            ->with('message', 'Complimenti, hai fatto richiesta per diventare revisore!');
    }

    public function makeRevisor(User $user)
    {
        Artisan::call('app:make-user-revisor', ['email' => $user->email]);
        return redirect()->back();
    }
}
