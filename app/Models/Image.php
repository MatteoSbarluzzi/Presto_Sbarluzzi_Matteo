<?php

namespace App\Models;

use App\Models\Image;
use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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

    //essendo static può essere chiamata direttamente sulla classe senza bisogno di creare un'istanza della classe
    public static function getUrlByFilePath($filePath, $w = null, $h = null)
    {
        //se $w o $h sono specificati, la funzione esegue il seguente codice
        if (!$w && !$h){
            return Storage::url($filePath);
        }
        $path = dirname($filePath);
        $filename = basename($filePath);
        $file = "{$path}/crop_{$w}x{$h}_{$filename}";//Costruisce un nuovo percorso del file con il nome crop_{w}x{h}_{filename} , che è la stessa struttura che abbiamo impostato in ResizeImage 
        return Storage::url($file);
    }

    public function getUrl($w = null, $h = null)// consente alle istanze di classe immagine di recuperare facilmente l'URL dell'immagine restituita da getUrlByFilePath 
    {
        return self::getUrlByFilePath($this->path, $w, $h);// fornisce la logica principale per recuperare l'URL dell'immagine, gestendo sia le immagini originali che quelle ritagliate
    }
}
