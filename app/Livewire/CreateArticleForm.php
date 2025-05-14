<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

class CreateArticleForm extends Component
{
    use WithFileUploads; // Importa il trait per la gestione dei file caricati

    public $images = []; // Array per memorizzare le immagini caricate
    public $temporary_images; //Gestire immagini temporanee appena caricate

    // Validazione in tempo reale non appena l'utente modifica il valore di una proprietà
    #[Validate('required|min:4')]
    public $title;

    #[Validate('required|min:10')]
    public $description;

    #[Validate('required|numeric')]
    public $price;

    #[Validate('required')]
    public $category;

    public $article;

    public function store()
    {
        $this->validate(); // Verifica che i dati siano validi prima di procedere

        $this->article = Article::create([
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category, 
            'user_id' => Auth::id()
        ]);

       // se l’utente ha inserito delle immagini, per ognuna di queste creiamo, tramite la funzione di relazione con article ($this->article->images() ), un oggetto di classe Image, il file sarà salvato nello storage e il percorso dell'immagine sarà salvato nella tabella images del database
       if (count($this->images) > 0){
            foreach ($this->images as $image){
                $this->article->images()->create(['path' => $image->store('images', 'public')]);
            }
       }

        // Usiamo una traduzione localizzata per il messaggio di successo
        session()->flash('success', __('messages.article_created_successfully'));
        $this->cleanForm(); // Pulisce il form dopo la creazione dell'articolo
    }

    public function cleanForm()
    {
        // Pulisce i campi del form al submit per evitare che rimangano le preview delle immagini precedenti
        $this->title = '';
        $this->description = '';
        $this->price = '';
        $this->category = '';
        $this->images = [];
    }

    public function render()
    {
        return view('livewire.create-article-form');
    }

    public function updatedTemporaryImages() // viene chiamato quando una proprietà pubblica di un componente viene modificata sul client. Questo hook fornisce un punto di accesso per reagire alle modifiche delle proprietà prima che il componente venga aggiornato sul server
    {
        if ($this->validate([
                'temporary_images.*' => 'image|max:1024',
                'temporary_images' => 'max:6' 
        ])) {
            foreach ($this->temporary_images as $image) {
                $this->images[] = $image; //sugar syntax, images è il nome dell'array che stiamo modificando, dopo l'uguale c'è il nome della variabile che stiamo assegnando; array_push($this->images, $image) nella sintassi classica
            }
        }
    }

    public function removeImage($key)
    {
        //Facciamo un controllo: se l’immagine selezionata è presente nell’array $images viene eliminata dall’array (e quindi non sarà né visualizzata né salvata
        if (in_array($key, array_keys($this->images))){
            unset($this->images[$key]); 
            //in_array() verifica se un dato (il primo parametro) è presente all’interno di un array (secondo parametro);  array_keys() : restituisce tutte le chiavi o indici dell’array passato come parametro;  unset() : elimina dati elementi dall’interno di un array
        }
    }
}
