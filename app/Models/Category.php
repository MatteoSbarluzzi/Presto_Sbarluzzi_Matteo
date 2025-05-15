<?php

namespace App\Models;

use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug', // aggiunto slug ai campi assegnabili
    ];

    // Relazione: una categoria ha molti articoli
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    // Hook Eloquent: genera automaticamente lo slug dal nome se non esiste
    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name, '_');
            }
        });
    }
}
