<?php

namespace App\Models;

use App\Models\Image;
use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


//Le immagini saranno caratterizate dal loro percorso (path) e dalla chiave esterna che le collegherà all'articolo a cui appartengono
class Image extends Model
{
    protected $fillable = [
        'path',
    ];

    public function article() : BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
