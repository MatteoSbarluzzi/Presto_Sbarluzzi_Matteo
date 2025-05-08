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
          <a class="nav-link active" aria-current="page" href="{{ route('homepage') }}">Home</a>
        </li>

        {{-- Link Tutti gli articoli --}}
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="{{ route('article.index') }}">Tutti gli articoli</a>
        </li>

        {{-- Dropdown categorie --}}
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Categorie
          </a>
          <ul class="dropdown-menu">
            @foreach ($categories as $category)
              <li>
                <a class="dropdown-item" href="{{ route('byCategory', ['category' => $category]) }}">{{ $category->name }}</a>
              </li>
              @if (!$loop->last) {{-- Se non è l'ultimo elemento della lista, aggiungi un divisore --}}
                <hr class="dropdown-divider">
              @endif
            @endforeach
          </ul>
        </li>

        {{-- Dropdown autenticazione --}}
        @auth
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Ciao, {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu">
              <li>
                <a class="dropdown-item" href="{{ route('create.article') }}">Crea</a>
              </li>
              <li>
                <a class="dropdown-item" href="#"
                  onclick="event.preventDefault(); document.querySelector('#form-logout').submit();">
                  Logout
                </a>
              </li>
              <form action="{{ route('logout') }}" method="POST" class="d-none" id="form-logout">@csrf</form>
            </ul>
          </li>
        @else
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Ciao, utente!
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ route('login') }}">Accedi</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="{{ route('register') }}">Registrati</a></li>
            </ul>
          </li>
        @endauth

      </ul>
    </div>
  </div>
</nav>
