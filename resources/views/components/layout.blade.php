<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Presto</title>
</head>
<body>
    
    <x-navbar/>

    <div class="min-vh-100 mt-lg-5">
        {{ $slot }}
    </div>
    
    <x-footer/>

    @vite('resources/js/app.js')
</body>
</html>
