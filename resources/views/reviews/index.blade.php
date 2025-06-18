<x-layout>
    <div class="container-fluid bg-beige py-5 mt-5">
        <div class="container">
            <h2 class="text-center mb-4">{{ __('ui.customer_reviews') }}</h2>

            {{-- Messaggio di successo --}}
            @if(session('message'))
                <div class="alert alert-success text-center">{{ session('message') }}</div>
            @endif

            {{-- Form per scrivere recensione (solo utenti autenticati) --}}
            @auth
                <div class="card mb-4 shadow">
                    <div class="card-body">
                        <form method="POST" action="{{ route('reviews.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="content" class="form-label">{{ __('ui.write_review') }}</label>
                                <textarea name="content" id="content" rows="4" class="form-control @error('content') is-invalid @enderror" required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="rating" class="form-label">{{ __('ui.rating') }}</label>
                                <select name="rating" id="rating" class="form-select @error('rating') is-invalid @enderror" required>
                                    <option value="">{{ __('ui.choose_rating') }}</option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" @selected(old('rating') == $i)>{{ $i }} ⭐</option>
                                    @endfor
                                </select>
                                @error('rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">{{ __('ui.send_review') }}</button>
                        </form>
                    </div>
                </div>
            @else
    <div class="text-center mb-5">
        <a href="{{ route('login') }}" class="btn-apply-filters">
            {{ __('ui.login_to_review') }}
        </a>
    </div>
@endauth


            {{-- Lista recensioni --}}
            <div class="row">
                @forelse ($reviews as $review)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow h-100">
                            <div class="card-body">
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
                @empty
                    <p class="text-center">{{ __('ui.no_reviews_yet') }}</p>
                @endforelse
            </div>

            {{-- Navigazione pagine --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
</x-layout>
