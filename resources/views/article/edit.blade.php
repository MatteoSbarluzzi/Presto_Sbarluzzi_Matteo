<x-layout>

    {{-- Contenitore principale --}}
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
                <form method="POST" action="{{ route('article.update', $article) }}" class="auth-form" enctype="multipart/form-data" id="articleEditForm">
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

                    {{-- Campo: Categoria --}}
                    <div class="mb-3">
                        <label for="category" class="form-label">{{ __('ui.select_category') }}</label>
                        <select class="form-select" id="category" name="category_id" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @if(old('category_id', $article->category_id) == $category->id) selected @endif>
                                    {{ __('ui.categories_list.' . $category->slug) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Campo: Carica nuove immagini --}}
                    <div class="mb-3">
                        <label for="images" class="form-label">{{ __('ui.upload_images') }}</label>
                        <input 
                            type="file" 
                            class="form-control" 
                            id="images" 
                            name="images[]" 
                            multiple 
                            accept="image/*"
                        >
                    </div>

                    {{-- Anteprima nuove immagini caricate --}}
                    <div class="mb-3" id="newImagePreview" style="display:flex; flex-wrap:wrap;"></div>

                    {{-- Mostra immagini esistenti con pulsante X --}}
                    @if ($article->images->count())
                        <div class="mb-3">
                            <p>{{ __('ui.current_images') }}:</p>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach ($article->images as $image)
                                    <div class="image-container me-2 mb-2 position-relative">
                                        <img src="{{ asset('storage/' . $image->path) }}" class="rounded shadow" alt="Immagine esistente" width="100" height="100">
                                        <button type="button" class="image-delete-btn position-absolute top-0 end-0 translate-middle badge rounded-pill bg-danger" data-image-id="{{ $image->id }}">
                                            &times;
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif


                    {{-- Campo nascosto: termine di ricerca se presente nella query string --}}
                    @if(request('query'))
                        <input type="hidden" name="query" value="{{ request('query') }}">
                    @endif

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
