<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> {{-- Imposta dinamicamente la lingua --}}
<head>
    <meta charset="UTF-8">
    <title>{{ __('ui.thank_you_for_subscribing') }}</title>
</head>
<body>

    {{-- Titolo dell’email --}}
    <h2>{{ __('ui.thank_you_for_subscribing') }}</h2>

    {{-- Rende sicuro il testo tradotto e converte gli a capo in <br> --}}
    <p>{!! nl2br(e(__('ui.newsletter_user_body'))) !!}</p>

</body>
</html>
