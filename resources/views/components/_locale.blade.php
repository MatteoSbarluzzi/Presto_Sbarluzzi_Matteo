{{-- L'utente fa una richiesta POST, equivalente al cambio di lingua --}}
<form class="d-inline" action="{{ route('setLocale', $lang) }}" method="POST"> {{-- Si aspetta la lingua selezionata --}}
    @csrf
    <button type="submit" class="dropdown-item d-flex align-items-center gap-2 border-0 bg-transparent w-100 text-start">
        {{-- img richiama il percorso interno all'oggetto per vedere l'immagine della bandiera --}}
        {{-- Il pacchetto blade-flags fornisce le SVG per le lingue in 'vendor/blade-flags' --}}
        <img src="{{ asset('vendor/blade-flags/language-' . $lang . '.svg') }}" width="32" height="32" />
        
        {{-- Nome della lingua, tradotto dinamicamente in base alla chiave corrispondente nel file ui.php --}}
        <span class="locale-label">
            {{ __('ui.' . ($lang == 'it' ? 'italian' : ($lang == 'en' ? 'english' : 'spanish'))) }}
        </span>
    </button>
</form>
