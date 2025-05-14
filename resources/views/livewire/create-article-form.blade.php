@if (session()->has('success'))
    <div class="alert alert-success text-center">
        {{ session('success') }}
    </div>
@endif

<form class="bg-body-tertiary shadow rounded p-5 my-5" wire:submit="store">
    <div class="mb-3">
        <label for="title" class="form-label">{{ __('ui.title') }}:</label>
        <input type="text" id="title" class="form-control @error('title') is-invalid @enderror" wire:model.blur="title">
        @error('title')
            <p class="fst-italic text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">{{ __('ui.description') }}:</label>
        <textarea id="description" rows="10" 
            class="form-control @error('description') is-invalid @enderror" wire:model.blur="description"></textarea>
        @error('description')
            <p class="fst-italic text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">{{ __('ui.price') }}:</label>
        <input type="text" id="price" class="form-control @error('price') is-invalid @enderror" wire:model.blur="price">
        @error('price')
            <p class="fst-italic text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-3">
        <select id="category" wire:model.blur="category" class="form-control @error('category') is-invalid @enderror">
    <option value="" disabled selected>{{ __('ui.select_category') }}</option>
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

    <div class="d-flex justify-content-center">
        <button type="submit" class="btn btn-dark">{{ __('ui.create') }}</button>
    </div>
</form>
