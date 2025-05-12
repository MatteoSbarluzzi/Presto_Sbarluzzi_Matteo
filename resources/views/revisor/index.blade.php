<x-layout>
    <div class="container-fluid pt-5">
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <div class="rounded shadow-lg bg-dark text-white text-center p-5">
                    <h1 class="display-4 fw-bold mb-3">
                        Revisor Dashboard
                    </h1>
                    <p class="lead">Benvenuto nella tua area di revisione</p>

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
                            <button class="btn btn-warning mt-3">Annulla ultima azione</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        @if ($article_to_check)
            <div class="row justify-content-center pt-5">
                <div class="col-md-8">
                    <div class="row justify-content-center">
                        @for ($i = 0; $i < 6; $i++)
                            <div class="col-6 col-md-4 mb-4 text-center">
                                <img src="https://picsum.photos/300"
                                     class="img-fluid rounded shadow"
                                     alt="immagine segnaposto" />
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="col-md-4 ps-4 d-flex flex-column justify-content-between">
                    <div>
                        <h1>{{ $article_to_check->title }}</h1>
                        <h3>Autore: {{ $article_to_check->user->name }} </h3>
                        <h4>{{ $article_to_check->price }}€</h4>
                        <h4 class="fst-italic text-muted">{{ $article_to_check->category->name }}</h4>
                        <p class="h6">{{ $article_to_check->description }}</p>
                    </div>

                    <div class="d-flex pb-4 justify-content-around">
                        <form action="{{ route('reject', ['article' => $article_to_check]) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-danger py-2 px-5 fw-bold">Rifiuta</button>
                        </form>
                        <form action="{{ route('accept', ['article' => $article_to_check]) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-success py-2 px-5 fw-bold">Accetta</button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="row justify-content-center align-items-center height-custom text-center">
                <div class="col-12">
                    <h1 class="fst-italic display-4">
                        Nessun articolo da revisionare
                    </h1>
                    <a href="{{ route('homepage') }}" class="mt-5 btn btn-success">Torna all’homepage</a>
                </div>
            </div>
        @endif
    </div>
</x-layout>
