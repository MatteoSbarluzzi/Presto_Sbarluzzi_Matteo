<div class="card mx-auto card-w shadow text-center mb-3 h-100">
    <img src="https://picsum.photos/200?random={{ $article->id }}" class="card-img-top" alt="Immagine articolo {{$article->title}}">

    <div class="card-body d-flex flex-column justify-content-between">
        <div>
            <h4 class="card-title">{{$article->title}}</h4>
            <h6 class="card-subtitle text-body-secondary">{{$article->price}} €</h6>
        </div>
        <div class="d-flex justify-content-evenly align-items-center mt-5">
            <a href="{{route('article.show', compact('article'))}}" class="btn btn-primary">Dettaglio</a>
            <a href="{{route('byCategory', ['category' => $article->category])}}" class="btn btn-outline-info">{{$article->category->name}}</a>
        </div>
    </div>
</div>
