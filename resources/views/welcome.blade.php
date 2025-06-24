<x-layout>
    {{-- Hero Section --}}
    <div id="backgroundpresto" class="container-fluid text-center d-flex align-items-center justify-content-center bg-dark-blue text-white py-5">
        <div class="row w-100">
            <div class="col-12">
                <h1 class="slide-down welcometitle">
                    {{ __('ui.homepage_title') }}
                </h1>

                @if (session()->has('errorMessage'))
                    <div class="alert alert-danger text-center shadow rounded w-50 mx-auto">
                        {{ session('errorMessage') }}
                    </div>
                @endif

                @if (session()->has('message'))
    <div class="alert alert-success text-center shadow rounded w-50 mx-auto">
        {{ __('ui.' . session('message')) }}
    </div>
@endif




                <div class="my-3">
                    @auth
                        <a class="btn-apply-filters slide-up" href="{{ route('create.article') }}">
                            {{ __('ui.publish_article') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- Titolo "Esplora le ultime inserzioni" --}}
    <div class="container-fluid bg-sky-blue text-beige py-3 pt-5">
        <h2 class="text-center slide-from-bottom-slow">{{ __('ui.explore_latest_ads') }}</h2>
    </div>

    {{-- Sezione Articoli --}}
    <div class="row height-custom justify-content-center align-items-stretch py-5 bg-sky-blue m-0">
        @forelse ($articles as $article)
            <div class="col-12 col-md-3 mb-4 d-flex article-fade-in">
                <x-card :article="$article"/>
            </div>
        @empty
            <div class="col-12">
                <h3 class="text-center text-white">
                    {{ __('ui.no_articles_yet') }}
                </h3>
            </div>
        @endforelse
    </div>

    {{-- Sezione Spedizioni Internazionali --}}
    <div class="container-fluid responsive-section bg-light-blue text-dark-blue py-5">
        <div class="row align-items-center">
            <div class="col-md-6 order-1 order-md-2 text-center slide-from-right mt-4 mt-md-0">
                <h2 class="fw-bold">{{ __('ui.shipping_worldwide') }}</h2>
                <p>{{ __('ui.shipping_description') }}</p>
                <a href="{{ route('shipping') }}" class="btn-apply-filters mt-3 mb-5">{{ __('ui.approfondisci') }}</a>
            </div>
            <div class="col-md-6 order-2 order-md-1 text-center slide-from-left">
                <img src="{{ asset('storage/images/logistics.png') }}" alt="Logistica" class="img-fluid shadow rounded">
            </div>
        </div>
    </div>

    {{-- Sezione Statistiche dinamiche --}}
    <div class="container-fluid responsive-section bg-orange text-dark-blue py-5">
        <div class="row align-items-center">
            <div class="col-md-6 slide-from-left">
                <h2 class="mb-4 text-center mt-5">{{ __('ui.our_numbers') }}</h2>

                <div class="mb-3 d-flex justify-content-between align-items-center px-3">
                    <p class="mb-0 fs-5">{{ __('ui.users_registered') }}</p>
                    <h3 id="firstNumber" class="display-4 mb-0">0</h3>
                </div>
                <div class="mb-3 d-flex justify-content-between align-items-center px-3">
                    <p class="mb-0 fs-5">{{ __('ui.articles_published') }}</p>
                    <h3 id="secondNumber" class="display-4 mb-0">0</h3>
                </div>
                <div class="d-flex justify-content-between align-items-center px-3 mb-5">
                    <p class="mb-0 fs-5">{{ __('ui.sales_record') }}</p>
                    <h3 id="thirdNumber" class="display-4 mb-0">0</h3>
                </div>
            </div>

            <div class="col-md-6 text-center slide-from-right">
                <img src="{{ asset('storage/images/progress.png') }}" alt="Statistiche Presto" class="img-fluid shadow rounded">
            </div>
        </div>
    </div>

    {{-- Sezione Recensioni --}}
    @if ($reviews->isNotEmpty())
        <div class="container-fluid responsive-section bg-beige text-white py-5">
            <div class="row w-100 justify-content-center position-relative">
                <div class="col-10 offset-1 col-sm-10 offset-sm-1 col-md-12 offset-md-0 col-lg-12 offset-lg-0">
                    <h2 class="text-center text-black mb-4 slide-from-bottom">{{ __('ui.latest_reviews') }}</h2>

                    <div class="swiper mySwiper position-relative">
                        <div class="swiper-wrapper">
                            @foreach ($reviews as $review)
                                <div class="swiper-slide review-fade-in">
                                    <div class="card shadow p-4 mx-auto" style="max-width: 600px;">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">{{ $review->user->name }}</h5>
                                            <p class="card-text fst-italic">"{{ $review->content }}"</p>
                                            <div class="mb-2">
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

                        <button id="prevButton" class="btn btn-light rounded-circle arrow-button start-0 translate-middle-y position-absolute top-50 d-none d-lg-flex">
                            <i class="bi bi-chevron-left"></i>
                        </button>

                        <button id="nextButton" class="btn btn-light rounded-circle arrow-button end-0 translate-middle-y position-absolute top-50 d-none d-lg-flex">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-layout>
