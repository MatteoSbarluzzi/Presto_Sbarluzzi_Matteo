<div class="card h-100 shadow text-center d-flex flex-column mx-auto mb-3" style="width: 100%; max-width: 300px;">
    
    {{-- Immagine in ratio 1:1 --}}
    <div class="ratio ratio-1x1">
        <img 
            src="{{ $article->images->isNotEmpty() ? $article->images->first()->getUrl(300, 300) : 'https://picsum.photos/200' }}" 
            class="img-fluid object-fit-cover rounded-top" 
            alt="Immagine dell'articolo {{ $article->title }}">
    </div>

    <div class="card-body d-flex flex-column justify-content-between">
        <div>
            <h4 class="card-title">{{ $article->title }}</h4>
            <h6 class="card-subtitle text-body-secondary">{{ $article->price }} €</h6>
        </div>

        <div class="mt-4">
            {{-- Bottoni azione affiancati --}}
            <div class="d-flex justify-content-center gap-2 mb-3">
                <a href="{{ route('article.show', compact('article')) }}" class="btn btn-primary">
                    {{ __('ui.detail') }}
                </a>

                @auth
                    @if(Auth::id() === $article->user_id || Auth::user()->is_revisor)
                        <form method="POST" action="{{ route('article.destroy', $article) }}" onsubmit="return confirm('{{ __('ui.confirm_delete') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">{{ __('ui.delete') }}</button>
                        </form>
                    @endif
                @endauth
            </div>

            {{-- Etichetta categoria --}}
            @php use Illuminate\Support\Str; @endphp
            <span class="badge rounded-pill text-info border border-info px-3 py-2">
                {{ __('ui.categories_list.' . Str::slug($article->category->name, '_')) }}
            </span>
        </div>
    </div>
</div>  
