<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use App\Mail\BecomeRevisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class RevisorController extends Controller
{
    // Mostra la dashboard revisore con il prossimo articolo da controllare
    public function index()
    {
        $article_to_check = Article::where('is_accepted', null)
                                   ->orderBy('updated_at', 'asc')
                                   ->first();

        return view('revisor.index', compact('article_to_check'));
    }

    // Accetta un articolo
    public function accept(Article $article)
    {
        // Recupera eventuali immagini da backup se sono scomparse
        if ($article->old_images && $article->images->isEmpty()) {
            foreach ($article->old_images as $oldPath) {
                $backupPath = 'backup/' . $oldPath;

                if (Storage::disk('public')->exists($backupPath)) {
                    Storage::disk('public')->copy($backupPath, $oldPath);
                }

                // Ricrea l'associazione nel DB se non esiste
                if (!$article->images()->where('path', $oldPath)->exists()) {
                    $article->images()->create(['path' => $oldPath]);
                }
            }
        }

        // Pulisce i dati temporanei salvati durante la modifica
        $article->update([
            'is_accepted' => true,
            'was_ever_accepted' => true,
            'old_title' => null,
            'old_description' => null,
            'old_price' => null,
            'old_category_id' => null,
            'old_images' => null,
        ]);

        // Elimina eventuali immagini di backup
        if ($article->old_images) {
            foreach ($article->old_images as $oldPath) {
                $backupPath = 'backup/' . $oldPath;
                if (Storage::disk('public')->exists($backupPath)) {
                    Storage::disk('public')->delete($backupPath);
                }
            }
        }

        // Salva l'ID dell'articolo appena revisionato per consentire l'annullamento
        session(['last_reviewed_article_id' => $article->id]);

        return redirect()
            ->route('revisor.index')
            ->with('message', __('messages.article_accepted', ['title' => $article->title]));
    }

    // Rifiuta un articolo e gestisce i tre casi (ripristino, eliminazione, rifiuto semplice)
    public function reject(Article $article)
    {
        $wasEverAccepted = $article->was_ever_accepted;

        $hasModifications = !is_null($article->old_title)
            || !is_null($article->old_description)
            || !is_null($article->old_price)
            || !is_null($article->old_category_id)
            || (is_array($article->old_images) && count($article->old_images) > 0);

        // Log utile per debugging lato server
        Log::debug('REJECT CHECK', [
            'article_id' => $article->id,
            'was_ever_accepted' => $wasEverAccepted,
            'hasModifications' => $hasModifications,
            'is_accepted' => $article->is_accepted,
        ]);

        // Caso 1: articolo già accettato e modificato → ripristina versione precedente
        if ($wasEverAccepted && $hasModifications) {
            Log::info("Restoring previous data for article ID {$article->id}");
            $article->restoreOldData();
            $article->is_accepted = true;
            $article->save();

        // Caso 2: mai accettato e senza modifiche → elimina
        } elseif (!$wasEverAccepted && !$hasModifications) {
            foreach ($article->images as $image) {
                Storage::disk('public')->delete($image->path);
            }
            $article->delete();

            session()->forget('last_reviewed_article_id');

            return redirect()
                ->route('revisor.index')
                ->with('message', __('ui.article_deleted_successfully'));

        // Caso 3: mai accettato ma modificato → solo rifiuto
        } else {
            $article->is_accepted = false;
            $article->save();
        }

        // Salva per possibile annullamento
        session(['last_reviewed_article_id' => $article->id]);

        return redirect()
            ->back()
            ->with('message', __('messages.article_rejected', ['title' => $article->title]));
    }

    // Annulla l’ultima revisione effettuata
    public function undoLastReview()
    {
        $articleId = session('last_reviewed_article_id');

        if ($articleId) {
            $article = Article::find($articleId);
            if ($article) {
                $article->is_accepted = null;
                $article->save();
                session()->forget('last_reviewed_article_id');

                return redirect()
                    ->route('revisor.index')
                    ->with('message', __('messages.review_undone', ['title' => $article->title]));
            }
        }

        return redirect()
            ->route('revisor.index')
            ->with('errorMessage', __('messages.no_action_to_undo'));
    }

    // Invia richiesta per diventare revisore (mail all’admin)
    public function becomeRevisor()
    {
        Mail::to('admin@presto.it')->send(new BecomeRevisor(Auth::user()));
        return redirect()
            ->route('homepage')
            ->with('message', 'revisor_request_thank_you');
    }

    // Rende un utente revisore tramite comando artisan
    public function makeRevisor(User $user)
    {
        Artisan::call('app:make-user-revisor', ['email' => $user->email]);
        return redirect()->back();
    }
}
