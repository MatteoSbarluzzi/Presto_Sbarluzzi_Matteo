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
    public function index()
    {
        $article_to_check = Article::where('is_accepted', null)
                                   ->orderBy('updated_at', 'asc')
                                   ->first();

        return view('revisor.index', compact('article_to_check'));
    }

    public function accept(Article $article)
    {
        $article->update([
            'is_accepted' => true,
            'was_ever_accepted' => true,
            'old_title' => null,
            'old_description' => null,
            'old_price' => null,
            'old_category_id' => null,
            'old_images' => null,
        ]);

        // ✅ Elimina eventuali immagini di backup
        if ($article->old_images) {
            foreach ($article->old_images as $oldPath) {
                $backupPath = 'backup/' . $oldPath;
                if (Storage::disk('public')->exists($backupPath)) {
                    Storage::disk('public')->delete($backupPath);
                }
            }
        }

        session(['last_reviewed_article_id' => $article->id]);

        return redirect()
            ->route('revisor.index')
            ->with('message', __('messages.article_accepted', ['title' => $article->title]));
    }

    public function reject(Article $article)
    {
        $wasEverAccepted = $article->was_ever_accepted;

        $hasModifications = !is_null($article->old_title)
            || !is_null($article->old_description)
            || !is_null($article->old_price)
            || !is_null($article->old_category_id)
            || (is_array($article->old_images) && count($article->old_images) > 0);

        Log::debug('REJECT CHECK', [
            'article_id' => $article->id,
            'was_ever_accepted' => $wasEverAccepted,
            'hasModifications' => $hasModifications,
            'is_accepted' => $article->is_accepted,
        ]);

        // ✅ Caso 1: articolo già accettato e modificato → ripristina versione precedente
        if ($wasEverAccepted && $hasModifications) {
            Log::info("Restoring previous data for article ID {$article->id}");
            $article->restoreOldData();
            $article->is_accepted = true;
            $article->save();

        // ✅ Caso 2: mai accettato e senza modifiche → elimina
        } elseif (!$wasEverAccepted && !$hasModifications) {
            foreach ($article->images as $image) {
                Storage::disk('public')->delete($image->path);
            }
            $article->delete();

            session()->forget('last_reviewed_article_id');

            return redirect()
                ->route('revisor.index')
                ->with('message', __('messages.article_deleted'));

        // ✅ Caso 3: mai accettato ma modificato → rifiuta semplicemente
        } else {
            $article->is_accepted = false;
            $article->save();
        }

        session(['last_reviewed_article_id' => $article->id]);

        return redirect()
            ->back()
            ->with('message', __('messages.article_rejected', ['title' => $article->title]));
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
                return redirect()->route('revisor.index')->with('message', __('messages.review_undone', ['title' => $article->title]));
            }
        }

        return redirect()->route('revisor.index')->with('errorMessage', __('messages.no_action_to_undo'));
    }

    public function becomeRevisor()
    {
        Mail::to('admin@presto.it')->send(new BecomeRevisor(Auth::user()));
        return redirect()->route('homepage')->with('message', __('ui.revisor_request_thank_you'));
    }

    public function makeRevisor(User $user)
    {
        Artisan::call('app:make-user-revisor', ['email' => $user->email]);
        return redirect()->back();
    }
}
