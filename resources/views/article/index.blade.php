<x-layout>
    <div class="container-fluid py-5 bg-sky-blue text-beige">
        {{-- Titolo sezione --}}
        <div class="row mb-5">
            <div class="col-12 text-center mt-4">
                <h1 class="display-4 slide-from-bottom-slow mt-4 mt-sm-5">
                    {{ __('ui.all_articles') }}
                </h1>
                @if (session('message'))
    <div class="alert alert-success text-center my-4 shadow rounded w-75 mx-auto">
        {{ session('message') }}
    </div>
@endif

            </div>
        </div>

        {{-- FILTRI + RISULTATI --}}
        <div class="row justify-content-center align-items-start">
            {{-- COLONNA FILTRI --}}
            <div class="col-12 col-md-3 px-4 sticky-filter filter-box">

                <h4 class="mb-4">{{ __('ui.filter_by') }}</h4>

                <form method="GET" action="{{ route('article.index') }}" id="filterForm">
                    {{-- CATEGORIA --}}
                    <div class="mb-3">
                        <label for="categorySelect" class="form-label">{{ __('ui.select_category') }}</label>
                        <select id="categorySelect" class="form-select" name="category">
                            <option value="all" @if(request('category') == 'all') selected @endif>{{ __('ui.all_categories') }}</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->slug }}" @if(request('category') == $cat->slug) selected @endif>{{ __('ui.categories_list.' . $cat->slug) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ORDINAMENTO --}}
                    <div class="mb-3">
                        <label for="sortSelect" class="form-label">{{ __('ui.sort_by') }}</label>
                        <select id="sortSelect" name="sort" class="form-select">
                            <option value="default" @if(request('sort') == 'default') selected @endif>{{ __('ui.default_order') }}</option>
                            <option value="title_asc" @if(request('sort') == 'title_asc') selected @endif>{{ __('ui.alpha_az') }}</option>
                            <option value="title_desc" @if(request('sort') == 'title_desc') selected @endif>{{ __('ui.alpha_za') }}</option>
                            <option value="price_asc" @if(request('sort') == 'price_asc') selected @endif>{{ __('ui.price_low_high') }}</option>
                            <option value="price_desc" @if(request('sort') == 'price_desc') selected @endif>{{ __('ui.price_high_low') }}</option>
                            <option value="oldest" @if(request('sort') == 'oldest') selected @endif>{{ __('ui.oldest_first') }}</option>
                            <option value="newest" @if(request('sort') == 'newest') selected @endif>{{ __('ui.newest_first') }}</option>
                        </select>
                    </div>

                    {{-- PREZZO --}}
                    <div class="mb-3">
                        <label for="priceInput" class="form-label">{{ __('ui.filter_by_price') }}</label>
                        <input type="range" class="form-range" name="price" id="priceInput"
                               min="0" max="{{ $maxPrice }}"
                               value="{{ request('price', $maxPrice) }}">
                        <div class="text-center">
                            <span id="priceValue">{{ request('price', $maxPrice) }}</span> €
                        </div>
                    </div>

                    {{-- PAROLA --}}
                    <div class="mb-3">
                        <label for="wordInput" class="form-label">{{ __('ui.search_by_word') }}</label>
                        <input type="text" id="wordInput" name="query" class="form-control" value="{{ request('query') }}" placeholder="{{ __('ui.search_placeholder') }}">
                    </div>

                    {{-- BOTTONI --}}
                    <div class="d-grid gap-2">
                        <button type="button" id="applyFiltersBtn" class="btn btn-apply-filters">
  {{ __('ui.apply_filters') }}
</button>

<a href="{{ route('article.index') }}" class="btn btn-reset-filters text-center">
  {{ __('ui.reset_filters') }}
</a>

                    </div>
                </form>
            </div>

            {{-- COLONNA CARDS --}}
            <div class="col-12 col-md-9">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 justify-content-center" id="cardWrapper">
                    @forelse ($articles as $article)
                        <div class="col d-flex article-fade-in">
                            <x-card :article="$article" />
                        </div>
                    @empty
                        <div class="col-12">
                            <h3 class="text-center">{{ __('ui.no_articles_yet') }}</h3>
                        </div>
                    @endforelse
                </div>

                {{-- Paginazione --}}
                <div class="mt-4 pt-4 d-flex justify-content-center">
                    {{ $articles->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layout>
