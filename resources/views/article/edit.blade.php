<x-layout>

    {{-- Contenitore principale: sfondo azzurro chiaro, testo beige, minimo altezza viewport, centrato --}}
    <div class="container-fluid bg-light-blue text-beige min-vh-100 d-flex flex-column justify-content-center">

        {{-- Titolo della pagina --}}
        <div class="row justify-content-center">
            <div class="col-12 text-center mt-5 mb-5">
                <h1 class="display-4 mt-5 mb-0">
                    {{ __('ui.edit_article') }}: 
                    <span class="text-orange">{{ $article->title }}</span>
                </h1>
            </div>
        </div>

        {{-- Contenitore del form centrato --}}
        <div class="row justify-content-center align-items-center height-custom mb-5">
            <div class="col-12 col-md-6">

                {{-- Se ci sono errori di validazione, mostra alert --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form di modifica articolo --}}
                <form method="POST" action="{{ route('article.update', $article) }}" class="auth-form">
                    @csrf
                    @method('PUT')

                    {{-- Campo: Titolo --}}
                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('ui.title') }}</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="title" 
                            name="title" 
                            value="{{ old('title', $article->title) }}" 
                            required
                        >
                    </div>

                    {{-- Campo: Descrizione --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('ui.description') }}</label>
                        <textarea 
                            class="form-control" 
                            id="description" 
                            name="description" 
                            rows="5" 
                            required
                        >{{ old('description', $article->description) }}</textarea>
                    </div>

                    {{-- Campo: Prezzo --}}
                    <div class="mb-3">
                        <label for="price" class="form-label">{{ __('ui.price') }}</label>
                        <input 
                            type="number" 
                            class="form-control" 
                            id="price" 
                            name="price" 
                            step="0.01" 
                            value="{{ old('price', $article->price) }}" 
                            required
                        >
                    </div>

                    {{-- Pulsante di salvataggio --}}
                    <div class="d-flex justify-content-center mt-4">
                        <button type="submit" class="btn-accept-custom">
                            {{ __('ui.save_changes') }}
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</x-layout>
