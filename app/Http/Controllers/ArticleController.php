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

class ArticleController extends Controller implements HasMiddleware
{
    // Middleware per proteggere solo la creazione articoli
    public static function middleware(): array
    {
        return [
            new Middleware('auth', only: ['create']),
        ];
    }

    // Metodo per la visualizzazione di tutti gli articoli (homepage "Tutti gli articoli")
    public function index(Request $request)
    {
        $maxPrice = Article::where('is_accepted', true)->max('price') ?? 0;
        $query = Article::where('is_accepted', true);

        // Filtro per categoria (slug)
        if ($request->filled('category') && $request->category !== 'all') {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
                $maxPrice = Article::where('is_accepted', true)->where('category_id', $category->id)->max('price') ?? 0;
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

        // Ordinamento articoli
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

        $articles = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('article.index', compact('articles', 'categories', 'maxPrice'));
    }

    // Metodo per visualizzare il dettaglio di un articolo
    public function show(Article $article, Request $request)
    {
        // Salvataggio URL precedente per il redirect dopo eliminazione
        $referer = $request->headers->get('referer');

        if ($referer) {
            if (str_contains($referer, '/categoria/')) {
                session(['previous_category_url' => $referer]);
            } elseif (str_contains($referer, '/searched')) {
                session(['previous_search_url' => $referer]);
            }
        }

        return view('article.show', compact('article'));
    }

    // Metodo per visualizzare articoli filtrati per categoria
    public function byCategory(Category $category, Request $request)
    {
        $query = $category->articles()->where('is_accepted', true);
        $maxPrice = $query->max('price') ?? 0;

        if ($request->filled('query')) {
            $query->where('title', 'like', '%' . $request->input('query') . '%');
        }

        if ($request->filled('price')) {
            $query->where('price', '<=', $request->price);
        }

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

        $articles = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('article.byCategory', compact('articles', 'category', 'categories', 'maxPrice'));
    }

    // Metodo per mostrare il form di creazione
    public function create()
    {
        return view('article.create');
    }

    // Metodo per mostrare il form di modifica dell’articolo
    public function edit(Article $article)
    {
        if (Auth::id() === $article->user_id || Auth::user()->is_revisor) {
            $categories = Category::all();
            return view('article.edit', compact('article', 'categories'));
        }

        return redirect()->back()->with('errorMessage', __('ui.not_authorized_to_edit'));
    }

    // Metodo per aggiornare l’articolo dopo la modifica
    public function update(Request $request, Article $article)
    {
        if (Auth::id() !== $article->user_id && !Auth::user()->is_revisor) {
            return redirect()->back()->with('errorMessage', __('ui.not_authorized_to_edit'));
        }

        // Validazione campi obbligatori
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|max:2048',
        ]);

        // Se l'articolo è già stato accettato, salva vecchi dati e rimanda in revisione
        if ($article->is_accepted === true) {
            $article->update([
                'old_title' => $article->title,
                'old_description' => $article->description,
                'old_price' => $article->price,
                'old_category_id' => $article->category_id,
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'category_id' => $request->input('category_id'),
                'is_accepted' => null,
            ]);
        } else {
            // Altrimenti aggiorna direttamente
            $article->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'category_id' => $request->input('category_id'),
            ]);
        }

        // Salva le nuove immagini caricate
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('articles', 'public');
                $article->images()->create(['path' => $path]);
            }
        }

        // Redirect dinamico in base alla provenienza
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

    // Metodo per eliminare un articolo
    public function destroy(Article $article, Request $request)
    {
        if (Auth::id() === $article->user_id || Auth::user()->is_revisor) {
            $article->delete();

            // Redirect personalizzato se specificato
            $redirectTo = $request->input('redirect_to');
            if ($redirectTo && str($redirectTo)->startsWith(url('/'))) {
                return redirect($redirectTo)->with('message', __('ui.article_deleted_successfully'));
            }

            // Redirect alla pagina precedente (se presente in sessione)
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
