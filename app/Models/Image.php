<?php

namespace App\Models;

use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Le immagini saranno caratterizzate dal loro percorso (path) e dalla chiave esterna che le collegherà all'articolo a cui appartengono
class Image extends Model
{
    protected $fillable = [
        'path',
    ];

   // Processo conversione automatica dei dati tra formati diversi che serve a convertire il campo   'labels' JSON in un array PHP automaticamente.
    protected $casts = [
        'labels' => 'array',
    ];

    // Relazione inversa: ogni immagine appartiene a un articolo
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    // Essendo static può essere chiamata direttamente sulla classe senza bisogno di creare un'istanza della classe
    public static function getUrlByFilePath($filePath, $w = null, $h = null)
    {
        // Se $w o $h non sono specificati, restituisce l'URL standard
        if (!$w && !$h) {
            return Storage::url($filePath);
        }

        // Costruisce un nuovo percorso del file con il nome crop_{w}x{h}_{filename}, che è la stessa struttura impostata in ResizeImage
        $path = dirname($filePath);
        $filename = basename($filePath);
        $file = "{$path}/crop_{$w}x{$h}_{$filename}";

        return Storage::url($file);
    }

    // Metodo di istanza: consente alle istanze della classe Image di recuperare facilmente l'URL dell'immagine
    public function getUrl($w = null, $h = null)
    {
        // Fornisce la logica principale per recuperare l'URL dell'immagine, gestendo sia le immagini originali che quelle ritagliate
        return self::getUrlByFilePath($this->path, $w, $h);
    }
}
