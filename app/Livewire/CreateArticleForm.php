<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;

class CreateArticleForm extends Component
{
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
            'category_id' => $this->category, // Assicurati che sia 'category_id'
            'user_id' => Auth::id()
        ]);

        $this->cleanForm(); // Pulisce il form dopo la creazione dell'articolo

        // Usiamo una traduzione localizzata per il messaggio di successo
        session()->flash('success', __('messages.article_created_successfully'));
    }

    public function cleanForm()
    {
        // Pulisce i campi del form
        $this->title = '';
        $this->description = '';
        $this->price = '';
        $this->category = '';
    }

    public function render()
    {
        return view('livewire.create-article-form');
    }
}
