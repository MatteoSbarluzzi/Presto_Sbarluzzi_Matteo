<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- Inclusione dello stile compilato da Vite --}}
    @vite('resources/css/app.css')

    <title>Presto</title>
</head>
<body>
    
    {{-- Navbar globale inclusa come componente Blade --}}
    <x-navbar/>

    {{-- Slot dinamico per i contenuti delle singole pagine --}}
    <div class="mt-lg-5">
        {{ $slot }}
    </div>
    
    {{-- Footer globale incluso come componente Blade --}}
    <x-footer/>

    {{-- Inclusione dello script JS compilato da Vite --}}
    @vite('resources/js/app.js')
</body>
</html>
