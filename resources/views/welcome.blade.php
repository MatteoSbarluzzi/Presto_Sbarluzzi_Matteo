<x-layout>
    <div class="container-fluid text-center bg-body-tertiary">
        <div class="row vh-100 justify-content-center align-items-center">
            <div class="col-12">
                <h1 class="display-4">Presto.it</h1>

                {{-- Messaggio di errore --}}
                @if (session()->has('errorMessage'))
                    <div class="alert alert-danger text-center shadow rounded w-50 mx-auto">
                        {{ session('errorMessage') }}
                    </div>
                @endif

                {{-- Messaggio di successo --}}
                @if (session()->has('message'))
                    <div class="alert alert-success text-center shadow rounded w-50 mx-auto">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="my-3">
                    @auth
                        <a class="btn btn-dark" href="{{ route('create.article') }}">Pubblica un articolo</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- Sezione Articoli --}}
    <div class="row height-custom justify-content-center align-items-center py-5">
        {{-- Verifichiamo che la collezione articles non sia vuota, così creiamo dinamicamente una card per ogni oggetto della collezione --}}
        @forelse ($articles as $article)
            <div class="col-12 col-md-3">
                <x-card :article="$article"/>
            </div>
        @empty
            <div class="col-12">
                <h3 class="text-center">
                    Non sono ancora stati creati articoli
                </h3>
            </div>
        @endforelse
    </div>
</x-layout>
