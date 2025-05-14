<div class="card mx-auto card-w shadow text-center mb-3 h-100">
    <img src="https://picsum.photos/200?random={{ $article->id }}" class="card-img-top" alt="{{ __('ui.image_for_article') }} {{ $article->title }}">

    <div class="card-body d-flex flex-column justify-content-between">
        <div>
            <h4 class="card-title">{{ $article->title }}</h4>
            <h6 class="card-subtitle text-body-secondary">{{ $article->price }} €</h6>
        </div>
        <div class="d-flex justify-content-evenly align-items-center mt-5">
            <a href="{{ route('article.show', compact('article')) }}" class="btn btn-primary">{{ __('ui.detail') }}</a>
            
            @php use Illuminate\Support\Str; @endphp
            <a href="{{ route('byCategory', ['category' => $article->category]) }}" class="btn btn-outline-info">
                {{ __('ui.categories_list.' . Str::slug($article->category->name, '_')) }}
            </a>
        </div>
    </div>
</div>
