<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// Import dei job Google Vision
use App\Jobs\GoogleVisionSafeSearch;
use App\Jobs\GoogleVisionLabelImage;

class ArticleController extends Controller implements HasMiddleware
{
    // Middleware per proteggere l'accesso a "create"
    public static function middleware(): array
    {
        return [
            new Middleware('auth', only: ['create']),
        ];
    }

    // Mostra tutti gli articoli filtrabili e ordinabili
    public function index(Request $request)
    {
        $maxPrice = Article::where('is_accepted', true)->max('price') ?? 0;
        $query = Article::where('is_accepted', true);

        // Filtro per categoria
        if ($request->filled('category') && $request->category !== 'all') {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
                $maxPrice = Article::where('is_accepted', true)
                    ->where('category_id', $category->id)
                    ->max('price') ?? 0;
            }
        }

        // Filtro per parola chiave
        if ($request->filled('query')) {
            $query->where('title', 'like', '%' . $request->input('query') . '%');
        }

        // Filtro per prezzo massimo
        if ($request->filled('price')) {
            $query->where('price', '<=', $request->price);
        }

        // Ordinamento
        switch ($request->get('sort')) {
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        // Paginazione e vista
        $articles = $query->with('images')->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('article.index', compact('articles', 'categories', 'maxPrice'));
    }

    // Mostra il dettaglio di un singolo articolo
    public function show(Article $article, Request $request)
    {
        $referer = $request->headers->get('referer');

        // Salvataggio referer per il ritorno alla pagina precedente
        if ($referer) {
            if (str_contains($referer, '/categoria/')) {
                session(['previous_category_url' => $referer]);
            } elseif (str_contains($referer, '/searched')) {
                session(['previous_search_url' => $referer]);
            }
        }

        return view('article.show', compact('article'));
    }

    // Visualizza articoli per categoria (con filtri)
    public function byCategory(Category $category, Request $request)
    {
        $query = $category->articles()->where('is_accepted', true);
        $maxPrice = $query->max('price') ?? 0;

        // Filtro per parola chiave
        if ($request->filled('query')) {
            $query->where('title', 'like', '%' . $request->input('query') . '%');
        }

        // Filtro per prezzo massimo
        if ($request->filled('price')) {
            $query->where('price', '<=', $request->price);
        }

        // Ordinamento
        switch ($request->get('sort')) {
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $articles = $query->with('images')->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('article.byCategory', compact('articles', 'category', 'categories', 'maxPrice'));
    }

    // Form per la creazione di un nuovo articolo
    public function create()
    {
        return view('article.create');
    }

    // Form di modifica (solo per autore o revisore)
    public function edit(Article $article)
    {
        if (Auth::id() === $article->user_id || Auth::user()->is_revisor) {
            $categories = Category::all();
            return view('article.edit', compact('article', 'categories'));
        }

        return redirect()->back()->with('errorMessage', __('ui.not_authorized_to_edit'));
    }

    // Aggiornamento articolo con backup se già accettato
    public function update(Request $request, Article $article)
    {
        // Autorizzazione
        if (Auth::id() !== $article->user_id && !Auth::user()->is_revisor) {
            return redirect()->back()->with('errorMessage', __('ui.not_authorized_to_edit'));
        }

        // Validazione input
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|max:2048',
            'images_to_delete' => 'array',
            'images_to_delete.*' => 'exists:images,id',
        ]);

        // Backup dati originali se è la prima modifica post-approvazione
        if (
            $article->was_ever_accepted === true &&
            is_null($article->old_title) &&
            is_null($article->old_description) &&
            is_null($article->old_price) &&
            is_null($article->old_category_id) &&
            is_null($article->old_images)
        ) {
            $validImagePaths = [];
            foreach ($article->images as $image) {
                if (Storage::disk('public')->exists($image->path)) {
                    $validImagePaths[] = $image->path;
                    $backupPath = 'backup/' . $image->path;
                    if (!Storage::disk('public')->exists($backupPath)) {
                        Storage::disk('public')->copy($image->path, $backupPath);
                    }
                }
            }

            $article->update([
                'old_title' => $article->title,
                'old_description' => $article->description,
                'old_price' => $article->price,
                'old_category_id' => $article->category_id,
                'old_images' => $validImagePaths,
            ]);
        }

        // Eliminazione immagini selezionate
        if ($request->filled('images_to_delete')) {
            foreach ($request->images_to_delete as $imageId) {
                $image = Image::find($imageId);
                if ($image && $image->article_id === $article->id) {
                    Storage::disk('public')->delete($image->path);
                    $image->delete();
                }
            }
        }

        // Aggiornamento dati articolo e reset dello stato di approvazione
        $article->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'category_id' => $request->input('category_id'),
            'is_accepted' => null,
        ]);

        // Caricamento nuove immagini e dispatch job Google Vision
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('articles', 'public');
                $image = $article->images()->create(['path' => $path]);

                GoogleVisionSafeSearch::dispatch($image->id);
                GoogleVisionLabelImage::dispatch($image->id);
            }
        }

        // Redirect alla pagina di provenienza
        if (session()->has('previous_category_url')) {
            return redirect(session()->pull('previous_category_url'))
                ->with('message', __('ui.article_submitted_for_review'));
        }

        if (session()->has('previous_search_url')) {
            return redirect(session()->pull('previous_search_url'))
                ->with('message', __('ui.article_submitted_for_review'));
        }

        return redirect()->route('article.index')
            ->with('message', __('ui.article_submitted_for_review'));
    }

    // Eliminazione articolo (con redirect intelligente)
    public function destroy(Article $article, Request $request)
    {
        if (Auth::id() === $article->user_id || Auth::user()->is_revisor) {
            $article->delete();

            // Redirect personalizzato se fornito
            $redirectTo = $request->input('redirect_to');
            if ($redirectTo && str($redirectTo)->startsWith(url('/'))) {
                return redirect($redirectTo)->with('message', __('ui.article_deleted_successfully'));
            }

            // Altri redirect possibili
            if (session()->has('previous_category_url')) {
                $url = session()->pull('previous_category_url');
                return redirect($url)->with('message', __('ui.article_deleted_successfully'));
            }

            if (session()->has('previous_search_url')) {
                $url = session()->pull('previous_search_url');
                return redirect($url)->with('message', __('ui.article_deleted_successfully'));
            }

            return redirect()->route('article.index')->with('message', __('ui.article_deleted_successfully'));
        }

        return redirect()->back()->with('errorMessage', __('ui.not_authorized_to_delete'));
    }
}
