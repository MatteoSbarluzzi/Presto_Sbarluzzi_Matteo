<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> {{-- Imposta dinamicamente la lingua --}}
<head>
    <meta charset="UTF-8">
    <title>{{ __('ui.new_newsletter_subscription') }}</title>
</head>
<body>

    {{-- Titolo dell’email --}}
    <h2>{{ __('ui.new_newsletter_subscription') }}</h2>

    {{-- Email dell’utente iscritto --}}
    <p>
        <strong>{{ __('ui.newsletter_email') }}:</strong> {{ $email }}
    </p>

    {{-- Testo di conferma --}}
    <p>{{ __('ui.newsletter_registered') }}</p>

</body>
</html>
