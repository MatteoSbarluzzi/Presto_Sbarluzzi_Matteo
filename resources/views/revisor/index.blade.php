<x-layout>
    <div class="container-fluid bg-sky-blue py-5">


        {{-- Titolo e sottotitolo --}}
        <div class="row justify-content-center mb-5">
            <div class="col-md-8 text-center pt-5">
                <h1 class="display-4 fw-bold text-beige">{{ __('ui.revisor_dashboard') }}</h1>
                <h2 class="lead text-beige">{{ __('ui.revisor_welcome') }}</h2>

                {{-- Messaggio di successo --}}
                @if (session()->has('message'))
                    <div class="alert alert-success text-center my-4 shadow rounded w-45">
                        {{ session('message') }}
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

                    {{-- IMMAGINI PROPOSTE --}}
                    <div class="row justify-content-center">
                        @php
                            // Se ci sono old_images, mostra tutte le immagini attuali (vecchie + nuove)
                            if ($article_to_check->old_images) {
                                $reviewImages = $article_to_check->images->pluck('path')->toArray();
                                $realImages = $article_to_check->images;
                            } else {
                                // Articolo mai approvato prima: mostra tutto normalmente
                                $reviewImages = $article_to_check->images->pluck('path')->toArray();
                                $realImages = $article_to_check->images;
                            }
                        @endphp

                        @if (count($reviewImages))
                            @foreach ($reviewImages as $key => $imagePath)
                                <div class="col-12 mb-4">
                                    <div class="card shadow">
                                        <div class="row g-0 align-items-center">

                                            {{-- Immagine --}}
                                            <div class="col-md-4">
                                                <img src="{{ asset('storage/' . $imagePath) }}"
                                                     class="img-fluid rounded-start"
                                                     alt="Immagine {{ $key + 1 }}">
                                            </div>

                                            {{-- Labels + Ratings --}}
                                            <div class="col-md-8 ps-3">
                                                <div class="card-body">
                                                    @php
                                                        $realImage = $realImages[$key] ?? null;
                                                    @endphp

                                                    {{-- Labels --}}
                                                    <h5 class="card-title">Labels</h5>
                                                    @if ($realImage && $realImage->labels)
                                                        @foreach ($realImage->labels as $label)
                                                            <span class="badge bg-dark text-light me-1">{{ $label }}</span>
                                                        @endforeach
                                                    @else
                                                        <p class="fst-italic">No labels</p>
                                                    @endif

                                                    {{-- Ratings --}}
                                                    <h5 class="card-title mt-4">Ratings</h5>
                                                    @if ($realImage)
                                                        <div class="mb-1 d-flex align-items-center gap-1">
                                                            <span class="me-1 text-capitalize" style="width: 70px">adult</span>
                                                            <i class="{{ $realImage->adult }}"></i>
                                                        </div>
                                                        <div class="mb-1 d-flex align-items-center gap-1">
                                                            <span class="me-1 text-capitalize" style="width: 70px">violence</span>
                                                            <i class="{{ $realImage->violence }}"></i>
                                                        </div>
                                                        <div class="mb-1 d-flex align-items-center gap-1">
                                                            <span class="me-1 text-capitalize" style="width: 70px">spoof</span>
                                                            <i class="{{ $realImage->spoof }}"></i>
                                                        </div>
                                                        <div class="mb-1 d-flex align-items-center gap-1">
                                                            <span class="me-1 text-capitalize" style="width: 70px">racy</span>
                                                            <i class="{{ $realImage->racy }}"></i>
                                                        </div>
                                                        <div class="mb-1 d-flex align-items-center gap-1">
                                                            <span class="me-1 text-capitalize" style="width: 70px">medical</span>
                                                            <i class="{{ $realImage->medical }}"></i>
                                                        </div>
                                                    @else
                                                        <p class="fst-italic">No ratings</p>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            {{-- Se non ci sono immagini, mostra segnaposto --}}
                            @for ($i = 0; $i < 6; $i++)
                                <div class="col-6 col-md-4 mb-4 text-center">
                                    <img src="https://picsum.photos/300" alt="Segnaposto" class="img-fluid rounded shadow">
                                </div>
                            @endfor
                        @endif
                    </div>
                </div>

                {{-- Colonna dettagli articolo e bottoni --}}
                <div class="col-12 col-md-4 d-flex flex-column justify-content-between mx-auto px-3 px-md-3">
                    <div class="bg-beige rounded p-4">

                        {{-- Dati modificati --}}
                        <h1 class="text-break">{{ $article_to_check->title }}</h1>
                        <h3 class="text-break">{{ __('ui.author') }}: {{ $article_to_check->user->name }}</h3>
                        <h4>{{ $article_to_check->price }}€</h4>

                        @php
                            $category = \App\Models\Category::find($article_to_check->category_id);
                        @endphp
                        @if ($category)
                            <h4 class="fst-italic text-muted text-break">{{ $category->getTranslatedName() }}</h4>
                        @endif

                        <p class="h6 text-break">{{ $article_to_check->description }}</p>

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
