<x-layout>
    <div class="container">
        <div class="row py-5 justify-content-center align-items-center text-center">
            <div class="col-12 pt-5">
                <h1 class="display-2">{{ __('ui.category_articles') }} <span class="fst-italic fw-bold">{{ $category->name }}</span></h1>
            </div>
        </div>
        <div class="row height-custom justify-content-center align-items-stretch py-5">
            @forelse ($articles as $article)
                <div class="col-12 col-md-3 mb-4 d-flex">
                    <x-card :article="$article" />
                </div>
            @empty
                <div class="col-12 text-center">
                    <h3>
                        {{ __('ui.no_articles_category') }}
                    </h3>
                    @auth
                        <a class="btn btn-dark my-5" href="{{route('create.article')}}">{{ __('ui.publish_article') }}</a>
                    @endauth
                </div>
            @endforelse
        </div>
    </div>
</x-layout>
