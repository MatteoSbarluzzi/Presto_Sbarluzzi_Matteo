<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;
use App\Jobs\ResizeImage;
use App\Jobs\GoogleVisionSafeSearch;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use App\Jobs\GoogleVisionLabelImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class CreateArticleForm extends Component
{
    use WithFileUploads; // Importa il trait per la gestione dei file caricati

    public $images = []; // Array per memorizzare le immagini caricate
    public $temporary_images; // Gestire immagini temporanee appena caricate

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

        // Salva le immagini caricate e lancia i job di elaborazione
        if (count($this->images) > 0) {
            foreach ($this->images as $image) {
                $newFileName = "articles/{$this->article->id}"; // Costruisce il nome del file per l'immagine con la struttura "articles/{id_articolo}"
                $newImage = $this->article->images()->create([ // Crea un nuovo record collegato all'articolo corrente nella tabella images tramite la relazione one-to-many tra articoli e immagini
                    'path' => $image->store($newFileName, 'public')]);
                dispatch(new ResizeImage($newImage->path, 300, 300)); // Crea un nuovo oggetto di classe ResizeImage e passa al costruttore i parametri reali: il path dell’immagine appena salvata e le dimensioni che vogliamo per il crop.
                dispatch(new GoogleVisionSafeSearch($newImage->id));
                dispatch(new GoogleVisionLabelImage($newImage->id));
                
            }
            File::deleteDirectory(storage_path('/app/livewire-tmp')); // Elimina la directory temporanea di Livewire, utilizzata per caricare temporaneamente le immagini prima del salvataggio
        }

        session()->flash('success', __('messages.article_created_successfully')); // Imposta un messaggio di successo nella sessione

        $this->cleanForm(); // Pulisce il form dopo la creazione dell'articolo

        return redirect()->route('homepage'); // Reindirizza l'utente alla homepage
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

    public function updatedTemporaryImages() // Viene chiamato quando una proprietà pubblica di un componente viene modificata sul client. Questo hook fornisce un punto di accesso per reagire alle modifiche delle proprietà prima che il componente venga aggiornato sul server
    {
        if ($this->validate([
                'temporary_images.*' => 'image|max:1024',
                'temporary_images' => 'max:6' 
        ])) {
            foreach ($this->temporary_images as $image) {
                $this->images[] = $image; // Aggiunge l'immagine all'array delle immagini caricate
            }
        }
    }

    public function removeImage($key)
    {
        // Controlla se l’immagine selezionata è presente nell’array $images e la elimina
        if (in_array($key, array_keys($this->images))){
            unset($this->images[$key]); 
        }
    }
}
