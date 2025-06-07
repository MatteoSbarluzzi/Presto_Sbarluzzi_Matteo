<x-layout>
    <div class="container-fluid mt-5 bg-light-blue text-beige min-vh-100">
        <div class="row justify-content-center">
            <div class="col-12 text-center mt-5">
                <h1 class="display-4 mt-5 mb-5">
                    {{ __('ui.login') }}
                </h1>
            </div>
        </div>
        <div class="row justify-content-center align-items-center height-custom">
            <div class="col-12 col-md-6">
                <form method="POST" action="{{ route('login') }}" class="auth-form p-5 my-3">
                    @csrf
                    <div class="mb-3">
                        <label for="loginEmail" class="form-label">{{ __('ui.email_address') }}</label>
                        <input type="email" class="form-control" id="loginEmail" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('ui.password') }}</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-submit">{{ __('ui.login') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
