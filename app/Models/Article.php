<?php

namespace App\Models;

use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
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
        //Valorizzazione dell'attributo is_accepted del singolo articolo col valore in ingresso
        $this->is_accepted = $value;
        $this->save();
        return true;
    }

    public static function toBeRevisionedCount()
    {
        //Facciamo una query al database per contare gli articoli che hanno il valore null nella colonna is_accepted . La query restituirà il numero di articoli che devono essere revisionati (una collezione)
        return Article::where('is_accepted', null)->count();
    }
}
