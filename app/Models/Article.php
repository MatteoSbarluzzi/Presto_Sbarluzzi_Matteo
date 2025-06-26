<?php

namespace App\Models;

use App\Models\User;
use App\Models\Image;
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
        'user_id',
        'is_accepted',
        'old_title',
        'old_description',
        'old_price',
        'old_category_id'
    ];

    // Un singolo oggetto di classe Article può appartenere ad un solo utente
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Un singolo oggetto di classe Article può appartenere ad una sola categoria
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Un singolo oggetto di classe Article può avere più oggetti di classe Image
    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    // Metodo per aggiornare lo stato di revisione
    public function isAccepted($value)
    {
        $this->is_accepted = $value;
        $this->save();
        return true;
    }

    // Metodo per contare gli articoli da revisionare
    public static function toBeRevisionedCount()
    {
        return Article::where('is_accepted', null)->count();
    }

    // Definizione dei dati da indicizzare nella ricerca
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
        ];
    }

    // Metodo per ottenere la chiave tradotta della categoria
    public function getTranslatedCategoryKey(): string
    {
        return 'ui.categories_list.' . str_replace('-', '_', $this->category->slug);
    }

    // Ripristina i dati salvati prima della modifica
    public function restoreOldData()
    {
        $this->update([
            'title' => $this->old_title,
            'description' => $this->old_description,
            'price' => $this->old_price,
            'category_id' => $this->old_category_id,
            'old_title' => null,
            'old_description' => null,
            'old_price' => null,
            'old_category_id' => null,
            'is_accepted' => true,
        ]);
    }

    // Elimina i dati temporanei salvati per la modifica
    public function clearOldData()
    {
        $this->update([
            'old_title' => null,
            'old_description' => null,
            'old_price' => null,
            'old_category_id' => null,
        ]);
    }
}
