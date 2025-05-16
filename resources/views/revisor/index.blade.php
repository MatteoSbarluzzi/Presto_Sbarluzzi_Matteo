<x-layout>
    <div class="container-fluid pt-5">
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <div class="rounded shadow-lg bg-dark text-white text-center p-5">
                    <h1 class="display-4 fw-bold mb-3">
                        {{ __('ui.revisor_dashboard') }}
                    </h1>
                    <p class="lead">{{ __('ui.revisor_welcome') }}</p>

                    {{-- Messaggio di successo --}}
                    @if (session()->has('message'))
                        <div class="alert alert-success mt-4">
                            {{ session('message') }}
                        </div>
                    @endif

                    {{-- Bottone annulla ultima revisione --}}
                    @if (session()->has('last_reviewed_article_id'))
                        <form action="{{ route('undo.last.review') }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-warning mt-3">{{ __('ui.undo_last_review') }}</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        @if ($article_to_check)
            <div class="row justify-content-center pt-5">
                <div class="col-md-8">
                    <div class="row justify-content-center">

                        {{-- Se l’articolo $article_to_check ha delle immagini, generiamo una card per ogni immagine associata.
                             Questo ci permette di mostrare, oltre all’immagine, anche le etichette (labels) e le icone salvate nel database 
                             dai job GoogleVisionSafeSearch e GoogleVisionLabelImage. --}}
                        @if ($article_to_check->images->count())
                            @foreach ($article_to_check->images as $key => $image)
                                <div class="col-12 mb-4">
                                    <div class="card shadow">
                                        <div class="row g-0 align-items-center">
                                            
                                            {{-- Colonna immagine (300x300) ritagliata dallo storage --}}
                                            <div class="col-md-4">
                                                {{-- uso corretto del metodo di istanza getUrl() --}}
                                                <img src="{{ $image->getUrl(300, 300) }}"
                                                     class="img-fluid rounded-start"
                                                     alt="Immagine {{ $key + 1 }} dell'articolo '{{ $article_to_check->title }}'">
                                            </div>

                                            {{-- Colonna centrale: mostra le etichette (labels) restituite da Google Vision --}}
                                            <div class="col-md-5 ps-3">
                                                <div class="card-body">
                                                    <h5 class="card-title">Labels</h5>
                                                    
                                                    {{-- Se ci sono etichette, le mostriamo come badge, altrimenti messaggio "No labels" --}}
                                                    @if ($image->labels)
                                                        @foreach ($image->labels as $label)
                                                            <span class="badge bg-dark text-light me-1">{{ $label }}</span>
                                                        @endforeach
                                                    @else
                                                        <p class="fst-italic">No labels</p>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Colonna destra: mostra le icone SafeSearch generate dal job GoogleVisionSafeSearch --}}
                                            <div class="col-md-3">
                                                <div class="card-body">
                                                    <h5 class="card-title">Ratings</h5>

                                                    {{-- Ogni riga mostra una valutazione AI con l’icona (salvata come classe CSS nel DB) e il nome del tipo --}}
                                                    <div class="row justify-content-center">
                                                        <div class="col-2">
                                                            <div class="text-center mx-auto"><i class="{{ $image->adult }}"></i></div>
                                                        </div>
                                                        <div class="col-10">adult</div>
                                                    </div>

                                                    <div class="row justify-content-center">
                                                        <div class="col-2">
                                                            <div class="text-center mx-auto"><i class="{{ $image->violence }}"></i></div>
                                                        </div>
                                                        <div class="col-10">violence</div>
                                                    </div>

                                                    <div class="row justify-content-center">
                                                        <div class="col-2">
                                                            <div class="text-center mx-auto"><i class="{{ $image->spoof }}"></i></div>
                                                        </div>
                                                        <div class="col-10">spoof</div>
                                                    </div>

                                                    <div class="row justify-content-center">
                                                        <div class="col-2">
                                                            <div class="text-center mx-auto"><i class="{{ $image->racy }}"></i></div>
                                                        </div>
                                                        <div class="col-10">racy</div>
                                                    </div>

                                                    <div class="row justify-content-center">
                                                        <div class="col-2">
                                                            <div class="text-center mx-auto"><i class="{{ $image->medical }}"></i></div>
                                                        </div>
                                                        <div class="col-10">medical</div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            {{-- Se non vi è alcuna immagine, mostriamo immagini segnaposto (6 placeholder) --}}
                            @for ($i = 0; $i < 6; $i++)
                                <div class="col-6 col-md-4 mb-4 text-center">
                                    <img src="https://picsum.photos/300" alt="immagine segnaposto" class="img-fluid rounded shadow">
                                </div>
                            @endfor
                        @endif

                    </div>
                </div>

                {{-- Colonna destra: dettagli dell’articolo e bottoni accetta/rifiuta --}}
                <div class="col-md-4 ps-4 d-flex flex-column justify-content-between">
                    <div>
                        <h1>{{ $article_to_check->title }}</h1>
                        <h3>{{ __('ui.author') }}: {{ $article_to_check->user->name }} </h3>
                        <h4>{{ $article_to_check->price }}€</h4>
                        <h4 class="fst-italic text-muted">{{ $article_to_check->category->name }}</h4>
                        <p class="h6">{{ $article_to_check->description }}</p>
                    </div>

                    {{-- Bottoni accetta/rifiuta con metodo PATCH --}}
                    <div class="d-flex pb-4 justify-content-around">
                        <form action="{{ route('reject', ['article' => $article_to_check]) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-danger py-2 px-5 fw-bold">{{ __('ui.reject') }}</button>
                        </form>
                        <form action="{{ route('accept', ['article' => $article_to_check]) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-success py-2 px-5 fw-bold">{{ __('ui.accept') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            {{-- Messaggio visibile quando non ci sono articoli da revisionare --}}
            <div class="row justify-content-center align-items-center height-custom text-center">
                <div class="col-12">
                    <h1 class="fst-italic display-4">
                        {{ __('ui.no_articles_to_review') }}
                    </h1>
                    <a href="{{ route('homepage') }}" class="mt-5 btn btn-success">{{ __('ui.back_home') }}</a>
                </div>
            </div>
        @endif
    </div>
</x-layout>
