<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::get('/articles-filtered', function(Request $request) {
        $query = \App\Models\Article::where('is_accepted', true);

        if ($request->filled('category') && $request->category !== 'all') {
            $category = \App\Models\Category::where('slug', $request->category)->first();
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

        $articles = $query->get();

        $result = $articles->map(function ($article) {
            return [
                'id' => $article->id,
                'title' => $article->title,
                'price' => $article->price,
                'category' => $article->category ? $article->category->slug : '',
                'detail_url' => route('article.show', $article->id),
                'image_url' => $article->images->isNotEmpty() ? $article->images->first()->getUrl(300,300) : 'https://picsum.photos/200',
            ];
        });

        return response()->json($result);
    });
});
