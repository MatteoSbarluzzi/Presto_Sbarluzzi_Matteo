{{-- L'utente fa una richiesta POST, equivalente al cambio di lingua --}}
<form class="d-inline" action="{{ route('setLocale', $lang) }}" method="POST"> {{-- Si aspetta la lingua selezionata --}}
    @csrf
    <button type="submit" class="btn">
        {{-- img richiama il percorso interno all'oggetto per vedere l'immagine della bandiera --}}
        <img src="{{ asset('vendor/blade-flags/language-' . $lang . '.svg') }}" width="32" height="32" />
 {{-- asset ha la funzione di generare url completi --}}
    </button>
</form>
