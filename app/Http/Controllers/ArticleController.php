<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller implements HasMiddleware
{
    // Solo gli utenti loggati possono accedere al metodo 'create'
    public static function middleware(): array
    {
        return [
            new Middleware('auth', only: ['create']),
        ];
    }

    // Mostra l'elenco degli articoli (paginati, dal più recente) con filtro prezzo dinamico
    public function index(Request $request)
    {
        $maxPrice = Article::where('is_accepted', true)->max('price') ?? 0;

        $query = Article::where('is_accepted', true);

        if ($request->filled('category') && $request->category !== 'all') {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($request->filled('word')) {
            $query->where('title', 'like', '%' . $request->word . '%');
        }

        if ($request->filled('price')) {
            $query->where('price', '<=', $request->price);
        }

        switch ($request->get('sort')) {
            case 'title_asc':
                $query->orderBy('title', 'asc');
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

    // Resto dei metodi senza modifiche
    public function show(Article $article)
    {
        return view('article.show', compact('article'));
    }

    public function byCategory(Category $category, Request $request)
    {
        $query = $category->articles()->where('is_accepted', true);

        if ($request->filled('word')) {
            $query->where('title', 'like', '%' . $request->word . '%');
        }

        if ($request->filled('price')) {
            $query->where('price', '<=', $request->price);
        }

        switch ($request->get('sort')) {
            case 'title_asc':
                $query->orderBy('title', 'asc');
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

        return view('article.byCategory', compact('articles', 'category'));
    }

    public function create()
    {
        return view('article.create');
    }

    public function destroy(Article $article)
    {
        if (Auth::id() === $article->user_id || Auth::user()->is_revisor) {
            $article->delete();
            return redirect()->back()->with('message', __('ui.article_deleted_successfully'));
        }

        return redirect()->back()->with('errorMessage', __('ui.not_authorized_to_delete'));
    }
}
