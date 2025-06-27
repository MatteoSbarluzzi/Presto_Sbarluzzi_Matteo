<x-layout>
    <div class="container-fluid bg-sky-blue py-5">
        
        {{-- Titolo e sottotitolo --}}
        <div class="row justify-content-center mb-5">
            <div class="col-md-8 text-center pt-5">
                <h1 class="display-4 fw-bold text-beige">{{ __('ui.revisor_dashboard') }}</h1>
                <h2 class="lead text-beige">{{ __('ui.revisor_welcome') }}</h2>
                
                {{-- Messaggio di successo --}}
                @if(session('message'))
                <div class="alert alert-success text-center">
                    {{ __('ui.' . session('message')) }}
                </div>
                @endif
                
                
                {{-- Bottone annulla ultima revisione --}}
                @if (session()->has('last_reviewed_article_id'))
                <form action="{{ route('undo.last.review') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-reject w-30 mt-3">{{ __('ui.undo_last_review') }}</button>
                </form>
                @endif
            </div>
        </div>
        
        {{-- Se c'è un articolo da revisionare --}}
        @if ($article_to_check)
        <div class="row justify-content-center pt-5">
            <div class="col-md-8">
                
                {{-- Se sono presenti immagini dell’articolo --}}
                <div class="row justify-content-center">
                    @if ($article_to_check->images->count())
                    @foreach ($article_to_check->images as $key => $image)
                    <div class="col-12 mb-4">
                        <div class="card shadow">
                            <div class="row g-0 align-items-center">
                                
                                {{-- Immagine --}}
                                <div class="col-md-4">
                                    <img src="{{ $image->getUrl(300, 300) }}"
                                    class="img-fluid rounded-start"
                                    alt="Immagine {{ $key + 1 }} dell'articolo '{{ $article_to_check->title }}'">
                                </div>
                                
                                {{-- Labels (etichettature AI) --}}
                                <div class="col-md-5 ps-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Labels</h5>
                                        @if ($image->labels)
                                        @foreach ($image->labels as $label)
                                        <span class="badge bg-dark text-light me-1">{{ $label }}</span>
                                        @endforeach
                                        @else
                                        <p class="fst-italic">No labels</p>
                                        @endif
                                    </div>
                                </div>
                                
                                {{-- Rating AI: contenuti sensibili --}}
                                <div class="col-md-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Ratings</h5>
                                        <div class="row align-items-center">
                                            <div class="col-2 text-center"><i class="{{ $image->adult }}"></i></div>
                                            <div class="col-10">adult</div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-2 text-center"><i class="{{ $image->violence }}"></i></div>
                                            <div class="col-10">violence</div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-2 text-center"><i class="{{ $image->spoof }}"></i></div>
                                            <div class="col-10">spoof</div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-2 text-center"><i class="{{ $image->racy }}"></i></div>
                                            <div class="col-10">racy</div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-2 text-center"><i class="{{ $image->medical }}"></i></div>
                                            <div class="col-10">medical</div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    {{-- Se non ci sono immagini, mostra 6 segnaposto --}}
                    @for ($i = 0; $i < 6; $i++)
                    <div class="col-6 col-md-4 mb-4 text-center">
                        <img src="https://picsum.photos/300" alt="immagine segnaposto" class="img-fluid rounded shadow">
                    </div>
                    @endfor
                    @endif
                </div>
            </div>
            
            {{-- Colonna dettagli articolo e bottoni --}}
            <div class="col-12 col-md-4 d-flex flex-column justify-content-between mx-auto px-3 px-md-3">
                <div class="bg-beige rounded p-4">
                    <h1>{{ $article_to_check->title }}</h1>
                    <h3>{{ __('ui.author') }}: {{ $article_to_check->user->name }}</h3>
                    <h4>{{ $article_to_check->price }}€</h4>
                    <h4 class="fst-italic text-muted">{{ $article_to_check->category->getTranslatedName() }}</h4>
                    <p class="h6">{{ $article_to_check->description }}</p>
                    
                    {{-- Bottoni accetta / rifiuta --}}
                    <div class="row g-2 mt-4">
                        <div class="col-6">
                            <form action="{{ route('accept', ['article' => $article_to_check]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-accept w-100" type="submit">
                                    {{ __('ui.accept') }}
                                </button>
                            </form>
                        </div>
                        <div class="col-6">
                            <form action="{{ route('reject', ['article' => $article_to_check]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-reject w-100" type="submit">
                                    {{ __('ui.reject') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Nessun articolo da revisionare --}}
        @else
        <div class="row justify-content-center align-items-center height-custom text-center">
            <div class="col-12">
                <h1 class="fst-italic display-4 text-beige">
                    {{ __('ui.no_articles_to_review') }}
                </h1>
            </div>
        </div>
        @endif
    </div>
</x-layout>
