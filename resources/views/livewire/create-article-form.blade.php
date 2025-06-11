{{-- create-article-form.blade.php --}}

@if (session()->has('success'))
    <div class="alert alert-success text-center">
        {{ session('success') }}
    </div>
@endif

{{-- identico “contenitore visivo” dei form di autenticazione --}}
<form class="auth-form p-5 my-3" wire:submit.prevent="store">
    {{-- Titolo --}}
    <div class="mb-3">
        <label for="title" class="form-label">{{ __('ui.title') }}:</label>
        <input  type="text"
                id="title"
                class="form-control @error('title') is-invalid @enderror"
                wire:model.blur="title">
        @error('title')
            <p class="fst-italic text-danger">{{ $message }}</p>
        @enderror
    </div>

    {{-- Descrizione --}}
    <div class="mb-3">
        <label for="description" class="form-label">{{ __('ui.description') }}:</label>
        <textarea id="description"
                  rows="10"
                  class="form-control @error('description') is-invalid @enderror"
                  wire:model.blur="description"></textarea>
        @error('description')
            <p class="fst-italic text-danger">{{ $message }}</p>
        @enderror
    </div>

    {{-- Prezzo --}}
    <div class="mb-3">
        <label for="price" class="form-label">{{ __('ui.price') }}:</label>
        <input  type="text"
                id="price"
                class="form-control @error('price') is-invalid @enderror"
                wire:model.blur="price">
        @error('price')
            <p class="fst-italic text-danger">{{ $message }}</p>
        @enderror
    </div>

    {{-- Selezione categoria --}}
    <div class="mb-3">
        <label for="category" class="form-label">{{ __('ui.select_category') }}</label>
        <select id="category"
                wire:model.defer="category"
                class="form-control @error('category') is-invalid @enderror">
            <option value="">{{ __('ui.select_category') }}</option> {{-- placeholder vuoto --}}
            @foreach($categories as $category)
                <option value="{{ $category->id }}">
                    {{ __('ui.categories_list.' . $category->slug) }}
                </option>
            @endforeach
        </select>
        @error('category')
            <p class="fst-italic text-danger">{{ $message }}</p>
        @enderror
    </div>

    {{-- Upload immagini --}}
    <div class="mb-3">
        <label for="temporary_images" class="form-label">{{ __('ui.image_for_article') }}:</label>
        <input  type="file"
                id="temporary_images"
                wire:model.live="temporary_images"
                multiple
                class="form-control shadow @error('temporary_images.*') is-invalid @enderror" />
        @error('temporary_images.*')
            <p class="fst-italic text-danger">{{ $message }}</p>
        @enderror
        @error('temporary_images')
            <p class="fst-italic text-danger">{{ $message }}</p>
        @enderror
    </div>

    {{-- Anteprima immagini --}}
    @if (!empty($images))
        <div class="row">
            <div class="col-12">
                <p>{{ __('ui.photo_preview') }}</p>
                <div class="row border border-4 border-success rounded shadow py-4">
                    @foreach ($images as $key => $image)
                        <div class="col d-flex flex-column align-items-center my-3">
                            <div  class="img-preview mx-auto shadow rounded"
                                  style="background-image: url({{ $image->temporaryUrl() }}); width: 150px; height: 150px; background-size: cover; background-position: center;"></div>
                            <button type="button"
                                    class="btn mt-1 btn-danger"
                                    wire:click="removeImage({{ $key }})">X</button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Pulsante di invio con stesso stile orange --}}
    <div class="d-flex justify-content-center">
        <button type="submit" class="btn btn-submit">{{ __('ui.create') }}</button>
    </div>
</form>
