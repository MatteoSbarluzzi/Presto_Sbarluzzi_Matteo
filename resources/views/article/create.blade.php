<x-layout>
    <div class="container-fluid bg-light-blue text-beige min-vh-100 d-flex flex-column justify-content-center">
        <div class="row justify-content-center">
            <div class="col-12 text-center mt-5 mb-5">
                <h1 class="display-4 mt-5 mb-0">{{ __('ui.publish_article') }}</h1>
            </div>
        </div>
        <div class="row justify-content-center align-items-center height-custom mb-5">
            <div class="col-12 col-md-6">
                <livewire:create-article-form />
            </div>
        </div>
    </div>
</x-layout>
