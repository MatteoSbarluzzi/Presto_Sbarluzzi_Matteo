<x-layout>
    <div class="container-fluid py-5 bg-sky-blue text-beige">
        {{-- Messaggio di errore --}}
        <div class="d-sm-none py-3"></div>
        <div class="row height-custom justify-content-center align-items-center text-center">
            <div class="col-12 mt-4">
                <h1 class="display-4 slide-from-bottom-slow mt-sm-5">{{ __('ui.article_detail', ['title' => $article->title]) }}</h1>
            </div>
        </div>

        <div class="row height-custom justify-content-center py-5">
            {{-- Immagini Carousel dinamico --}}
            <div class="col-12 col-md-6 mb-3">
                @if ($article->images->count() > 0)
                    <div id="carouselExample" class="carousel slide">
                        <div class="carousel-inner">
                            @foreach ($article->images as $key => $image)
                                <div class="carousel-item @if ($loop->first) active @endif">
                                    <img src="{{ $image->getUrl(300, 300) }}" class="d-block w-100 rounded shadow"
                                         alt="Immagine {{ $key + 1 }} dell'articolo {{ $article->title }}">
                                </div>
                            @endforeach
                        </div>

                        @if ($article->images->count() > 1)
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
                    <img src="https://picsum.photos/300" alt="Nessuna foto inserita dall'utente">
                @endif
            </div>

            {{-- Dettagli Articolo --}}
            <div class="col-12 col-md-6 mb-3 height-custom text-center">
                <h2 class="display-5">
                    <span class="fw-bold">{{ __('ui.title') }}: </span> {{ $article->title }}
                </h2>
                <div class="d-flex flex-column justify-content-center h-75">
                    <h4 class="fw-bold">{{ __('ui.price') }}: {{ $article->price }} €</h4>
                    <h5>{{ __('ui.description') }}:</h5>
                    <p>{{ $article->description }}</p>

                    {{-- Etichetta categoria --}}
                    <span class="badge rounded-pill text-info border border-info px-3 py-2 my-2 w-auto align-self-center">
                        {{ __('ui.categories_list.' . Illuminate\Support\Str::slug($article->category->name, '_')) }}
                    </span>
                </div>

                {{-- BOTTONI CHIUSURA E CANCELLA --}}
                <div class="d-flex justify-content-center gap-2 mt-3">
                    <a href="{{ url()->previous() }}" class="btn-close-detail">
                        {{ __('ui.close_article_detail') }}
                    </a>

                    @auth
                        @if(Auth::id() === $article->user_id || Auth::user()->is_revisor)
                            <form method="POST" action="{{ route('article.destroy', $article) }}"
                                  onsubmit="return confirm('{{ __('ui.confirm_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="redirect_to" value="{{ url()->previous() }}">
                                <button type="submit" class="btn-delete-custom">{{ __('ui.delete') }}</button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-layout>
