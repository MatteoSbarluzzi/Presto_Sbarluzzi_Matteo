<?php

namespace App\Models;

use App\Models\User;
use App\Models\Image;
use App\Models\Article;
use App\Models\Category;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use Searchable;

    protected $fillable = [
        'title',
        'description',
        'price',
        'category_id',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function isAccepted($value)
    {
        // Valorizzazione dell'attributo is_accepted del singolo articolo col valore in ingresso
        $this->is_accepted = $value;
        $this->save();
        return true;
    }

    public static function toBeRevisionedCount()
    {
        // Facciamo una query al database per contare gli articoli che hanno il valore null nella colonna is_accepted.
        // La query restituirà il numero di articoli che devono essere revisionati (una collezione)
        return Article::where('is_accepted', null)->count();
    }

    public function toSearchableArray() // Metodo che viene definito in un modello Eloquent per personalizzare i dati da indicizzare
    {
        return [
            // Definisce i campi che verranno indicizzati per la ricerca
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category // Stiamo richiamando la relazione category() per ottenere i dati della categoria associata all'articolo, non il nome di una colonna nella tabella
        ];
    }

    // Un singolo oggetto di classe Article può avere più oggetti di classe Image
    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }
}
