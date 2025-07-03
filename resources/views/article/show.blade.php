<x-layout>
    {{-- Contenitore con padding e sfondo colorato --}}
    <div class="container-fluid pt-5 pb-5 bg-sky-blue text-beige">
        
        {{-- MESSAGGI DI SUCCESSO O ERRORE --}}
        @if (session('message'))
        <div class="alert alert-success text-center">
            {{ session('message') }}
        </div>
        @endif
        
        @if (session('errorMessage'))
        <div class="alert alert-danger text-center">
            {{ session('errorMessage') }}
        </div>
        @endif
        
        {{-- Spazio aggiuntivo su mobile --}}
        <div class="d-sm-none py-3"></div>
        
        {{-- TITOLO PRINCIPALE --}}
        <div class="row justify-content-center align-items-center text-center">
            <div class="col-12 mt-4">
                <h1 class="display-4 slide-from-bottom-slow mt-sm-5">
                    {{ __('ui.article_detail', ['title' => $article->old_title ?? $article->title]) }}
                </h1>
            </div>
        </div>
        
        {{-- SEZIONE IMMAGINI + DETTAGLI ARTICOLO --}}
        <div class="row justify-content-center py-5">
            
            {{-- COLONNA IMMAGINI - CAROUSEL --}}
            <div class="col-12 col-md-6 mb-3">
                @php
                    // Se sono presenti old_images (modifica in revisione),
                    // mostra solo le immagini attualmente approvate
                    $reviewImages = $article->old_images
                        ? $article->old_images
                        : $article->images->pluck('path')->toArray();
                @endphp

                @if (count($reviewImages) > 0)
                <div id="carouselExample" class="carousel slide">
                    <div class="carousel-inner">
                        @foreach ($reviewImages as $key => $imagePath)
                        <div class="carousel-item @if ($loop->first) active @endif">
                            <img 
                            src="{{ asset('storage/' . $imagePath) }}" 
                            class="d-block w-100 rounded shadow"
                            alt="Immagine {{ $key + 1 }} dell'articolo {{ $article->title }}">
                        </div>
                        @endforeach
                    </div>
                    
                    {{-- Controlli Carousel (se più di una immagine) --}}
                    @if (count($reviewImages) > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">{{ __('ui.previous') }}</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">{{ __('ui.next') }}</span>
                    </button>
                    @endif
                </div>
                @else
                {{-- Immagine placeholder se non ci sono immagini --}}
                <img src="https://picsum.photos/300" alt="Nessuna foto inserita dall'utente">
                @endif
            </div>
            
            {{-- COLONNA DETTAGLI ARTICOLO --}}
            <div class="col-12 col-md-6 mb-3 text-center">
                {{-- Titolo --}}
                <h2 class="display-5">
                    <span class="fw-bold">{{ __('ui.title') }}: </span> {{ $article->old_title ?? $article->title }}
                </h2>
                
                <div class="d-flex flex-column justify-content-center">
                    {{-- Prezzo --}}
                    <h4 class="fw-bold my-3">
                        {{ __('ui.price') }}: {{ $article->old_price ?? $article->price }} €
                    </h4>
                    
                    {{-- Descrizione --}}
                    <h5>{{ __('ui.description') }}:</h5>
                    <p>{{ $article->old_description ?? $article->description }}</p>
                    
                    {{-- Categoria come badge --}}
                    @php
                        $category_id = $article->old_category_id ?? $article->category_id;
                        $category = \App\Models\Category::find($category_id);
                    @endphp
                    @if ($category)
                    <span class="badge rounded-pill text-info border border-info px-3 py-2 my-2 w-auto align-self-center">
                        {{ __('ui.categories_list.' . \Illuminate\Support\Str::slug($category->name, '_')) }}
                    </span>
                    @endif
                    
                    {{-- COLONNA BOTTONI --}}
                    <div class="button-column-wrapper mt-3">
                        
                        {{-- Bottone CHIUDI --}}
                        <a href="{{ request('back') ?? route('article.index') }}" class="btn-close-detail btn-detail-action">
                            {{ __('ui.close_article_detail') }}
                        </a>
                        
                        {{-- Bottone MODIFICA e CANCELLA (solo per proprietario o revisore) --}}
                        @auth
                        @if(Auth::id() === $article->user_id || Auth::user()->is_revisor)
                        
                        {{-- Bottone MODIFICA --}}
                        <a href="{{ route('article.edit', $article) }}" class="btn-edit-custom btn-detail-action mt-2">
                            {{ __('ui.edit_article') }}
                        </a>
                        
                        {{-- Form CANCELLA --}}
                        <form method="POST" action="{{ route('article.destroy', $article) }}"
                        onsubmit="return confirm('{{ __('ui.confirm_delete') }}')">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="redirect_to" value="{{ request('back') ?? route('article.index') }}">
                            <button type="submit" class="btn-delete-custom btn-detail-action mt-2">
                                {{ __('ui.delete') }}
                            </button>
                        </form>
                        @endif
                        @endauth
                    </div>
                    {{-- FINE COLONNA BOTTONI --}}
                </div>
                
                {{-- Autore dell'articolo --}}
                <p class="text-muted fw-bold my-3">
                    {{ __('ui.created_by') }}: <strong>{{ $article->user->name }}</strong>
                </p>
                
            </div>
        </div>
    </div>
</x-layout>
