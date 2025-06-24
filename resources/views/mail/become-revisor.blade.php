<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> {{-- Rende dinamica la lingua della pagina --}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Presto.it</title>
</head>
<body>
    <div>
        {{-- Titolo principale --}}
        <h1>{{ __('ui.revisor_request') }}</h1>

        {{-- Dati utente che ha richiesto di diventare revisore --}}
        <h2>{{ __('ui.user_info') }}</h2>
        <p>{{ __('ui.name') }}: {{ $user->name }}</p>
        <p>{{ __('ui.email') }}: {{ $user->email }}</p>

        {{-- Invito all'admin a cliccare per nominare revisore --}}
        <p>{{ __('ui.revisor_click') }}</p>

        {{-- Link per promuovere l'utente a revisore --}}
        <a href="{{ route('make.revisor', compact('user')) }}">
            {{ __('ui.make_revisor') }}
        </a>
    </div>
</body>
</html>
