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

                        {{-- Se l’articolo $article_to_check ha delle immagini (ovvero se il ognuna delle immagini creiamo una colonna e un tag count() della collezione restituisce un numero maggiore di 0), per img, altrimenti, se non vi è alcuna immagine, vedremo sempre l'immagine segnaposto --}}
                        @if ($article_to_check->images->count())
                            @foreach ($article_to_check->images as $key => $image)
                                <div class="col-6 col-md-4 mb-4 text-center">
                                    {{-- uso corretto del metodo di istanza getUrl() --}}
                                    <img src="{{ $image->getUrl(300, 300) }}" class="img-fluid rounded shadow"
                                         alt="Immagine {{ $key + 1 }} dell'articolo '{{ $article_to_check->title }}'">
                                </div>
                            @endforeach
                        @else
                            @for ($i = 0; $i < 6; $i++)
                                <div class="col-6 col-md-4 mb-4 text-center">
                                    <img src="https://picsum.photos/300" alt="immagine segnaposto"
                                         class="img-fluid rounded shadow">
                                </div>
                            @endfor
                        @endif

                    </div>
                </div>

                <div class="col-md-4 ps-4 d-flex flex-column justify-content-between">
                    <div>
                        <h1>{{ $article_to_check->title }}</h1>
                        <h3>{{ __('ui.author') }}: {{ $article_to_check->user->name }} </h3>
                        <h4>{{ $article_to_check->price }}€</h4>
                        <h4 class="fst-italic text-muted">{{ $article_to_check->category->name }}</h4>
                        <p class="h6">{{ $article_to_check->description }}</p>
                    </div>

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
