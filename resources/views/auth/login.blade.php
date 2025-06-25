<x-layout>
    {{-- Contenitore principale con sfondo, colore testo e altezza minima schermo --}}
    <div class="container-fluid mt-5 bg-light-blue text-beige min-vh-100">

        {{-- Titolo pagina --}}
        <div class="row justify-content-center">
            <div class="col-12 text-center mt-5">
                <h1 class="display-4 mt-5 mb-5">
                    {{ __('ui.login') }}
                </h1>
            </div>
        </div>

        {{-- Sezione contenente il form --}}
        <div class="row justify-content-center align-items-center height-custom">
            <div class="col-12 col-md-6">

                {{-- Messaggio di errore login --}}
                    @if(session('error'))
                        <div class="alert alert-danger text-center">
                            {{ session('error') }}
                        </div>
                    @endif


                {{-- Form di login --}}
                <form method="POST" action="{{ route('login') }}" class="auth-form p-5 my-3">
                    @csrf

                    {{-- Campo Email --}}
                    <div class="mb-3">
                        <label for="loginEmail" class="form-label">{{ __('ui.email_address') }}</label>
                        <input type="email" class="form-control" id="loginEmail" name="email">
                    </div>

                    {{-- Campo Password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('ui.password') }}</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>

                    {{-- Bottone invio --}}
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-submit">
                            {{ __('ui.login') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-layout>
