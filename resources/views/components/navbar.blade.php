{{-- Navbar --}}
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    {{-- Brand --}}
    <a class="navbar-brand" href="{{ route('homepage') }}">Presto.it</a>

    {{-- Toggler per dispositivi mobili --}}
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    {{-- Contenuto della navbar --}}
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">

        {{-- Link Home --}}
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="{{ route('homepage') }}">{{ __('ui.home') }}</a>
        </li>

        {{-- Link Tutti gli articoli --}}
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="{{ route('article.index') }}">{{ __('ui.all_articles') }}</a>
        </li>

{{-- Dropdown categorie --}}
@php use Illuminate\Support\Str; @endphp
<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    {{ __('ui.categories') }}
  </a>
  <ul class="dropdown-menu">
    @foreach ($categories as $category)
      <li>
        <a class="dropdown-item" href="{{ route('byCategory', ['category' => $category]) }}">
          {{ __('ui.categories_list.' . Str::slug($category->name, '_')) }}
        </a>
      </li>
      @if (!$loop->last)
        <hr class="dropdown-divider">
      @endif
    @endforeach
  </ul>
</li>


        {{-- Link Zona Revisore per utenti revisori --}}
        @auth
          @if (Auth::user()->is_revisor)
            <li class="nav-item">
              <a class="nav-link btn btn-outline-success btn-sm position-relative w-sm-25"
                 href="{{ route('revisor.index') }}">
                {{ __('ui.revisor_zone') }}
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                  {{ \App\Models\Article::toBeRevisionedCount() }}
                </span>
              </a>
            </li>
          @endif
        @endauth

        {{-- Dropdown autenticazione --}}
        @auth
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              {{ __('ui.hello_user', ['name' => Auth::user()->name]) }}
            </a>
            <ul class="dropdown-menu">
              <li>
                <a class="dropdown-item" href="{{ route('create.article') }}">{{ __('ui.create') }}</a>
              </li>
              <li>
                <a class="dropdown-item" href="#"
                  onclick="event.preventDefault(); document.querySelector('#form-logout').submit();">
                  {{ __('ui.logout') }}
                </a>
              </li>
              <form action="{{ route('logout') }}" method="POST" class="d-none" id="form-logout">@csrf</form>
            </ul>
          </li>
        @else
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              {{ __('ui.hello_guest') }}
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ route('login') }}">{{ __('ui.login') }}</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="{{ route('register') }}">{{ __('ui.register') }}</a></li>
            </ul>
          </li>
        @endauth

        {{-- Lingue disponibili --}}
        <li class="nav-item"><x-_locale lang="it" /></li>
        <li class="nav-item"><x-_locale lang="en" /></li>
        <li class="nav-item"><x-_locale lang="es" /></li>

      </ul>

      {{-- FORM DI RICERCA --}}
      <form class="d-flex ms-auto" role="search" action="{{ route('article.search') }}" method="GET">
        <div class="input-group">
          <input type="search" name="query" class="form-control" placeholder="{{ __('ui.search_placeholder') }}" aria-label="search">
          <button type="submit" class="input-group-text btn btn-outline-success" id="basic-addon2">
            {{ __('ui.search') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</nav>
