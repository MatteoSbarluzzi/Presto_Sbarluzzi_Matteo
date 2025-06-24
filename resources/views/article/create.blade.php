<x-layout>
    
    {{-- Contenitore principale con sfondo, colore testo e altezza minima per riempire la viewport --}}
    <div class="container-fluid bg-light-blue text-beige min-vh-100 d-flex flex-column justify-content-center">

        {{-- Titolo della pagina --}}
        <div class="row justify-content-center">
            <div class="col-12 text-center mt-5 mb-5">
                <h1 class="display-4 mt-5 mb-0">
                    {{ __('ui.publish_article') }}
                </h1>
            </div>
        </div>

        {{-- Contenitore del form centrato verticalmente --}}
        <div class="row justify-content-center align-items-center height-custom mb-5">
            <div class="col-12 col-md-6">
                
                {{-- Componente Livewire per la creazione dell'articolo --}}
                <livewire:create-article-form />

            </div>
        </div>
        
    </div>

</x-layout>
