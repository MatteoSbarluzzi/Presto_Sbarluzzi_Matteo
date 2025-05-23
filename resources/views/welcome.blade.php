<x-layout>
    <div id="backgroundpresto" class="container-fluid text-center d-flex align-items-center justify-content-center">
        <div class="row w-100">
            <div class="col-12">
                <h1 class="slide-down welcometitle">{{ __('ui.homepage_title') }}</h1>

                {{-- Messaggio di errore --}}
                @if (session()->has('errorMessage'))
                    <div class="alert alert-danger text-center shadow rounded w-50 mx-auto">
                        {{ session('errorMessage') }}
                    </div>
                @endif

                {{-- Messaggio di successo --}}
                @if (session()->has('message'))
                    <div class="alert alert-success text-center shadow rounded w-50 mx-auto">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="my-3">
                    @auth
                        <a class="btn btn-dark slide-up" href="{{ route('create.article') }}">{{ __('ui.publish_article') }}</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- Sezione Articoli --}}
    <div class="row height-custom justify-content-center align-items-stretch py-5">
        {{-- Verifichiamo che la collezione articles non sia vuota, così creiamo dinamicamente una card per ogni oggetto della collezione --}}
        @forelse ($articles as $article)
            <div class="col-12 col-md-3 mb-4 d-flex">
                <x-card :article="$article"/>
            </div>
        @empty
            <div class="col-12">
                <h3 class="text-center">
                    {{ __('ui.no_articles_yet') }}
                </h3>
            </div>
        @endforelse
    </div>

    {{-- Sezione Spedizioni Internazionali --}}
    <div class="container my-5">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0 text-center">
                <img src="{{ asset('images/world-map-shipping.jpg') }}" alt="Spedizioni nel mondo" class="img-fluid shadow rounded">
            </div>
            <div class="col-md-6 text-center text-md-start">
                <h2 class="mb-3">{{ __('ui.shipping_worldwide') }}</h2>
                <p class="lead">
                    {{ __('ui.shipping_description', ['countries' => 35]) }}
                </p>
            </div>
        </div>
    </div>

    {{-- Sezione Statistiche dinamiche --}}
    <div class="container my-5">
        <h2 class="text-center mb-4">{{ __('ui.our_numbers') }}</h2>
        <div class="row text-center">
            <div class="col-md-4">
                <h3 id="firstNumber" class="display-4">0</h3>
                <p>{{ __('ui.users_registered') }}</p>
            </div>
            <div class="col-md-4">
                <h3 id="secondNumber" class="display-4">0</h3>
                <p>{{ __('ui.articles_published') }}</p>
            </div>
            <div class="col-md-4">
                <h3 id="thirdNumber" class="display-4">0</h3>
                <p>{{ __('ui.sales_record') }}</p>
            </div>
        </div>
    </div>

    {{-- Sezione Recensioni --}}
    @if ($reviews->isNotEmpty())
        <div class="container my-5">
            <h2 class="text-center mb-4">{{ __('ui.latest_reviews') }}</h2>

            <div id="reviewsCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">

                    @foreach ($reviews as $index => $review)
                        <div class="carousel-item @if($index === 0) active @endif">
                            <div class="card mx-auto shadow p-4" style="max-width: 600px;">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $review->user->name }}</h5>
                                    <p class="card-text fst-italic">"{{ $review->content }}"</p>
                                    <div class="mb-2">
                                        {{-- Visualizzazione stelle valutazione --}}
                                        @for ($i = 0; $i < 5; $i++)
                                            <i class="bi @if($i < $review->rating) bi-star-fill @else bi-star @endif text-warning"></i>
                                        @endfor
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

                {{-- Controlli del carosello --}}
                <button class="carousel-control-prev" type="button" data-bs-target="#reviewsCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#reviewsCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    @endif
</x-layout> 