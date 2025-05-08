<?php

namespace App\Models;

use App\Models\Article;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name'
    ];

    // Relazione: una categoria ha molti articoli
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
