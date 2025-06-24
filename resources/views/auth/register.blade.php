<x-layout>
    {{-- Contenitore con sfondo, testo chiaro e altezza minima a schermo pieno --}}
    <div class="container-fluid bg-light-blue text-beige min-vh-100 d-flex flex-column justify-content-center">

        {{-- Titolo pagina --}}
        <div class="row justify-content-center">
            <div class="col-12 text-center mt-5 mb-5">
                <h1 class="display-4 mt-5 mb-0">
                    {{ __('ui.register') }}
                </h1>
            </div>
        </div>

        {{-- Sezione centrale con form --}}
        <div class="row justify-content-center align-items-center height-custom mb-5">
            <div class="col-12 col-md-6">

                {{-- Form di registrazione --}}
                <form method="POST" action="{{ route('register') }}" class="auth-form p-5 my-3">
                    @csrf

                    {{-- Campo Nome --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('ui.name') }}:</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>

                    {{-- Campo Email --}}
                    <div class="mb-3">
                        <label for="registerEmail" class="form-label">{{ __('ui.email_address') }}:</label>
                        <input type="email" class="form-control" id="registerEmail" name="email">
                    </div>

                    {{-- Campo Password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('ui.password') }}:</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>

                    {{-- Campo Conferma Password --}}
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">{{ __('ui.confirm_password') }}:</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>

                    {{-- Bottone di invio --}}
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-submit">
                            {{ __('ui.register') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-layout>
