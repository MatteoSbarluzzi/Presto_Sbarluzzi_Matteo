<?php

namespace App\Models;

use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    // Attributi assegnabili in massa
    protected $fillable = [
        'name',
        'slug',
    ];

    // Relazione: una categoria ha molti articoli
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    // Hook Eloquent: genera automaticamente lo slug dal nome se non già presente
    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name, '_');
            }
        });
    }

    // Restituisce il nome della categoria tradotto (in base al file ui.php e alla lingua corrente)
    public function getTranslatedName()
    {
        return __('ui.categories_list.' . $this->slug);
    }
}
