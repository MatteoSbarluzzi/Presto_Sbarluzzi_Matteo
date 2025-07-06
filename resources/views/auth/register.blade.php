<x-layout>

    {{-- Contenitore principale --}}
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
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                        @error('name')
                            <div class="text-danger mt-1">{{ __('ui.name_required') }}</div>
                        @enderror
                    </div>

                    {{-- Campo Email --}}
                    <div class="mb-3">
                        <label for="registerEmail" class="form-label">{{ __('ui.email_address') }}:</label>
                        <input type="email" class="form-control" id="registerEmail" name="email" value="{{ old('email') }}">
                        @error('email')
                            @if(str_contains($message, 'required'))
                                <div class="text-danger mt-1">{{ __('ui.email_required') }}</div>
                            @elseif(str_contains($message, 'valid'))
                                <div class="text-danger mt-1">{{ __('ui.email_invalid') }}</div>
                            @elseif(str_contains($message, 'taken') || str_contains($message, 'been taken'))
                                <div class="text-danger mt-1">{{ __('ui.email_taken') }}</div>
                            @else
                                <div class="text-danger mt-1">{{ $message }}</div> {{-- fallback --}}
                            @endif
                        @enderror
                    </div>

                    {{-- Campo Password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('ui.password') }}:</label>
                        <input type="password" class="form-control" id="password" name="password">
                        @error('password')
                            @if(str_contains($message, 'confirmation'))
                                <div class="text-danger mt-1">{{ __('ui.passwords_do_not_match') }}</div>
                            @else
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @endif
                        @enderror
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
