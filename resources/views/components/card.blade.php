<div class="card mx-auto card-w shadow text-center mb-3 h-100">
    {{-- $article->images->isNotEmpty() : controlliamo che la collezione delle immagini relazionate all’articolo non sia vuota --}}
    <img src="{{ $article->images->isNotEmpty() ? $article->images->first()->getUrl(300, 300) : 'https://picsum.photos/200' }}" {{-- getUrl... verifica  se la condizione è rispettata, e quindi c'è almeno una immagine, verrà 
eseguito questo codice altrimenti quella di lorem picsum di default. Inoltre al metodo statico della classe Storage 
url() , utilizzato per generare un URL pubblico per un file archiviato 
nel sistema di storage, passiamo il path della prima immagine della collezione relazionata all’articolo --}} 
         class="card-img-top" 
         alt="Immagine dell'articolo {{ $article->title }}">

    <div class="card-body d-flex flex-column justify-content-between">
        <div>
            <h4 class="card-title">{{ $article->title }}</h4>
            <h6 class="card-subtitle text-body-secondary">{{ $article->price }} €</h6>
        </div>
        <div class="d-flex justify-content-evenly align-items-center mt-5">
            <a href="{{ route('article.show', compact('article')) }}" class="btn btn-primary">{{ __('ui.detail') }}</a>
            
            @php use Illuminate\Support\Str; @endphp
            <a href="{{ route('byCategory', ['category' => $article->category]) }}" class="btn btn-outline-info">
                {{ __('ui.categories_list.' . Str::slug($article->category->name, '_')) }}
            </a>
        </div>
    </div>
</div>
