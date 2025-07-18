<?php

namespace App\Models;

use App\Models\User;
use App\Models\Image;
use App\Models\Category;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use Searchable;

    // Campi assegnabili in massa
    protected $fillable = [
        'title',
        'description',
        'price',
        'category_id',
        'user_id',
        'is_accepted',
        'was_ever_accepted',
        'old_title',
        'old_description',
        'old_price',
        'old_category_id',
        'old_images',
    ];

    // Conversione automatica per tipi complessi
    protected $casts = [
        'old_images' => 'array',
        'was_ever_accepted' => 'boolean',
    ];

    // Relazione: un articolo appartiene a un utente
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relazione: un articolo appartiene a una categoria
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Relazione: un articolo ha molte immagini
    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    // Conta gli articoli ancora da revisionare
    public static function toBeRevisionedCount()
    {
        return self::where('is_accepted', null)->count();
    }

    // Dati da indicizzare per la ricerca
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
        ];
    }

    // Ritorna la chiave di traduzione della categoria
    public function getTranslatedCategoryKey(): string
    {
        return 'ui.categories_list.' . str_replace('-', '_', $this->category->slug);
    }

    // Ripristina i dati precedenti salvati prima della modifica
    public function restoreOldData()
    {
        $this->restoreOldImages();

        $this->update([
            'title' => $this->old_title,
            'description' => $this->old_description,
            'price' => $this->old_price,
            'category_id' => $this->old_category_id,
            'old_title' => null,
            'old_description' => null,
            'old_price' => null,
            'old_category_id' => null,
            'old_images' => null,
        ]);
    }

    // Cancella i campi di backup delle modifiche
    public function clearOldData()
    {
        $this->update([
            'old_title' => null,
            'old_description' => null,
            'old_price' => null,
            'old_category_id' => null,
            'old_images' => null,
        ]);
    }

    // Ritorna il valore da mostrare nel form revisore (modifica proposta o valore attuale)
    public function getReviewValue(string $field)
    {
        $oldField = 'old_' . $field;
        return $this->$oldField ?? $this->$field;
    }

    // Ripristina le immagini precedenti salvate in backup
    public function restoreOldImages()
    {
        if (!empty($this->old_images)) {
            // Elimina immagini attuali
            foreach ($this->images as $image) {
                if (Storage::disk('public')->exists($image->path)) {
                    Storage::disk('public')->delete($image->path);
                }
                $image->delete();
            }

            // Ripristina immagini da backup o da path originale
            foreach ($this->old_images as $oldPath) {
                if (!$this->images()->where('path', $oldPath)->exists()) {
                    if (Storage::disk('public')->exists('backup/' . $oldPath)) {
                        Storage::disk('public')->copy('backup/' . $oldPath, $oldPath);
                    }
                    $this->images()->create(['path' => $oldPath]);
                }
            }

            // Pulisce il campo old_images una volta ripristinato tutto
            $this->old_images = null;
            $this->save();
        }
    }
}
