<div class="card card-custom card-fixed-width h-100 shadow text-center d-flex flex-column mx-auto mb-3">

    {{-- Immagine in ratio 1:1 --}}
    <div class="ratio ratio-1x1">
        <img 
            src="{{ $article->images->isNotEmpty() ? asset('storage/' . $article->images->first()->path) : 'https://picsum.photos/200' }}" 
            class="img-fluid object-fit-cover rounded-top" 
            alt="Immagine dell'articolo {{ $article->title }}">
    </div>

    <div class="card-body d-flex flex-column justify-content-between">
        <div>
            <h4 class="card-title">{{ $article->title }}</h4>
            <h6 class="card-subtitle">{{ $article->price }} €</h6>
        </div>

        <div class="mt-4">
            <div class="d-flex justify-content-center gap-2 mb-3">
                {{-- Link alla pagina di dettaglio con parametro "back" per tornare correttamente alla pagina corrente --}}
                <a href="{{ route('article.show', ['article' => $article, 'back' => request()->fullUrl()]) }}" class="btn btn-detail">
                    {{ __('ui.detail') }}
                </a>

                {{-- Pulsante elimina visibile solo all’autore o al revisore --}}
                @auth
                    @if(Auth::id() === $article->user_id || Auth::user()->is_revisor)
                        <form method="POST" action="{{ route('article.destroy', $article) }}" onsubmit="return confirm('{{ __('ui.confirm_delete') }}')">
                            @csrf
                            @method('DELETE')
                            {{-- Redireziona alla pagina corrente dopo l’eliminazione --}}
                            <input type="hidden" name="redirect_to" value="{{ url()->full() }}">
                            <button type="submit" class="btn btn-outline-danger">{{ __('ui.delete') }}</button>
                        </form>
                    @endif
                @endauth
            </div>

            {{-- Etichetta categoria tradotta dinamicamente in base allo slug --}}
            <span 
                class="badge rounded-pill px-3 py-2 text-truncate d-inline-block w-100" 
                data-bs-toggle="tooltip" 
                data-bs-placement="top" 
                title="{{ __($article->getTranslatedCategoryKey()) }}">
                {{ __($article->getTranslatedCategoryKey()) }}
            </span>
        </div>
    </div>
</div>
