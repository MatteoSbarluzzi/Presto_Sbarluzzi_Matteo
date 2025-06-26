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
    // Mostra il pannello del revisore con un articolo da revisionare (il primo con is_accepted = null)
    public function index()
    {
        $article_to_check = Article::where('is_accepted', null)->first();
        return view('revisor.index', compact('article_to_check')); //salviamo il primo articolo che corrisponde alla condizione avere nella colonna null e passiamo questo dato alla vista
    }

    // Accetta l'articolo e lo salva come approvato
    public function accept(Article $article)
    {
        // Se ci sono modifiche precedenti, vengono azzerate
        $article->update([
            'is_accepted' => true,
            'old_title' => null,
            'old_description' => null,
            'old_price' => null,
            'old_category_id' => null,
        ]);

        session(['last_reviewed_article_id' => $article->id]);

        return redirect()
            ->route('revisor.index') //redirect alla rotta corretta dopo l'approvazione
            ->with('message', __('messages.article_accepted', ['title' => $article->title]));
    }

    // Rifiuta l'articolo e lo salva come non approvato (oppure ripristina i dati precedenti)
    public function reject(Article $article)
    {
        // Se l'articolo è una modifica già approvata (dati old_* presenti), li ripristiniamo
        if (
            $article->old_title !== null ||
            $article->old_description !== null ||
            $article->old_price !== null ||
            $article->old_category_id !== null
        ) {
            $article->update([
                'title' => $article->old_title,
                'description' => $article->old_description,
                'price' => $article->old_price,
                'category_id' => $article->old_category_id,
                'is_accepted' => true,
                'old_title' => null,
                'old_description' => null,
                'old_price' => null,
                'old_category_id' => null,
            ]);
        } else {
            // Se non è una modifica, semplicemente viene rifiutato
            $article->isAccepted(false);
        }

        session(['last_reviewed_article_id' => $article->id]);

        return redirect()
            ->back() //torna indietro alla pagina corrente
            ->with('message', __('messages.article_rejected', ['title' => $article->title]));
    }

    // Annulla l'ultima revisione effettuata (reimposta is_accepted a null)
    public function undoLastReview()
    {
        $articleId = session('last_reviewed_article_id');
        if ($articleId) {
            $article = Article::find($articleId);
            if ($article) {
                $article->is_accepted = null;
                $article->save();
                session()->forget('last_reviewed_article_id');
                return redirect()->route('revisor.index')->with('message', __('messages.review_undone', ['title' => $article->title]));
            }
        }

        return redirect()->route('revisor.index')->with('errorMessage', __('messages.no_action_to_undo'));
    }

    // Invia la richiesta per diventare revisore tramite email
    public function becomeRevisor()
    {
        Mail::to('admin@presto.it')->send(new BecomeRevisor(Auth::user()));

        return redirect()
            ->route('homepage')
            ->with('message', __('ui.revisor_request_thank_you'));
    }

    // Assegna il ruolo di revisore a un utente tramite Artisan Command personalizzato
    public function makeRevisor(User $user)
    {
        Artisan::call('app:make-user-revisor', ['email' => $user->email]);
        return redirect()->back();
    }
}
