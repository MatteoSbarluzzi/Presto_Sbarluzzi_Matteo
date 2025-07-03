{{-- Navbar --}}
<nav class="navbar navbar-expand-lg fixed-top navcustom">
  <div class="container">
    
    {{-- Logo mobile --}}
    <a class="navbar-brand d-lg-none logo-wrapper" href="{{ route('homepage') }}">
      <img src="{{ asset('storage/images/prestoit_logo.png') }}" class="logo" alt="Presto.it logo">
    </a>
    
    {{-- Bottone toggle mobile --}}
    <button class="navbar-toggler text-white border-0 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"><div></div><span>
    </button>
    
    {{-- Menu principale della navbar --}}
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        
        {{-- Logo desktop (visibile solo su schermi grandi) --}}
        <li class="nav-item logo-wrapper d-none d-lg-flex">
          <a class="navbar-brand" href="{{ route('homepage') }}">
            <img src="{{ asset('storage/images/prestoit_logo.png') }}" class="logo" alt="Presto.it logo">
          </a>
        </li>
        
        {{-- Link Home --}}
        <li class="nav-item">
          <a class="nav-link" href="{{ route('homepage') }}">{{ __('ui.home') }}</a>
        </li>
        
        {{-- Link Tutti gli articoli --}}
        <li class="nav-item">
          <a class="nav-link" href="{{ route('article.index') }}">{{ __('ui.all_articles') }}</a>
        </li>
        
        {{-- Dropdown Categorie --}}
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            {{ __('ui.categories') }}
          </a>
          <ul class="dropdown-menu">
            @foreach ($categories as $category)
            <li>
              <a class="dropdown-item" href="{{ route('byCategory', ['category' => $category]) }}">
                {{ __('ui.categories_list.' . $category->slug) }}
              </a>
            </li>
            @if (!$loop->last)
            <hr class="dropdown-divider">
            @endif
            @endforeach
          </ul>
        </li>
        
        {{-- Zona revisore visibile solo se utente autenticato e revisore --}}
        @auth
        @if (Auth::user()->is_revisor)
        <li class="nav-item">
          <a class="nav-link position-relative" href="{{ route('revisor.index') }}">
            {{ __('ui.revisor_zone') }}
            {{-- Badge rosso con il numero di articoli da revisionare --}}
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              {{ \App\Models\Article::toBeRevisionedCount() }}
            </span>
          </a>
        </li>
        @endif
        @endauth
        
        {{-- Area autenticazione --}}
        @auth
        {{-- Dropdown per utente autenticato --}}
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            {{ __('ui.hello_user', ['name' => Auth::user()->name]) }}
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('create.article') }}">{{ __('ui.create') }}</a></li>
            <li><a class="dropdown-item" href="{{ route('reviews') }}">{{ __('ui.reviews') }}</a></li>
            <li>
              <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.querySelector('#form-logout').submit();">
                {{ __('ui.logout') }}
              </a>
            </li>
            {{-- Form invisibile per logout --}}
            <form action="{{ route('logout') }}" method="POST" class="d-none" id="form-logout">@csrf</form>
          </ul>
          
        </li>
        @else
        {{-- Dropdown per ospite --}}
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            {{ __('ui.hello_guest') }}
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('login') }}">{{ __('ui.login') }}</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="{{ route('register') }}">{{ __('ui.register') }}</a></li>
          </ul>
        </li>
        @endauth
        
        {{-- Dropdown selezione lingua --}}
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
            {{-- Bandiera lingua attuale --}}
            <img src="{{ asset('vendor/blade-flags/language-' . app()->getLocale() . '.svg') }}" width="32" height="32" />
            <span class="text-current-language">{{ __('ui.' . (app()->getLocale() == 'it' ? 'italian' : (app()->getLocale() == 'en' ? 'english' : 'spanish'))) }}</span>
          </a>
          
          <ul class="dropdown-menu">
            @foreach (['it', 'en', 'es'] as $lang)
            <li>
              {{-- Cambio lingua tramite POST --}}
              <form action="{{ route('setLocale', ['lang' => $lang]) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="dropdown-item d-flex align-items-center gap-2 border-0 bg-transparent w-100 text-start">
                  {{-- Bandiera lingua --}}
                  <x-_locale :lang="$lang" />
                  <span>{{ __('ui.' . ($lang == 'it' ? 'italian' : ($lang == 'en' ? 'english' : 'spanish'))) }}</span>
                </button>
              </form>
            </li>
            @endforeach
          </ul>
        </li>
        
        {{-- Campo di ricerca --}}
        <li class="nav-item">
          <form class="d-flex" role="search" action="{{ route('article.search') }}" method="GET">
            <div class="input-group">
              <input type="search" name="query" class="form-control" placeholder="{{ __('ui.search_placeholder') }}" aria-label="search">
              <button type="submit" class="input-group-text btn-search-custom">
                {{ __('ui.search') }}
              </button>
            </div>
          </form>
        </li>
        
      </ul>
    </div>
  </div>
</nav>
