<?php

namespace App\Jobs;

use Spatie\Image\Image;
use Spatie\Image\Enums\CropPosition;
use Spatie\Image\Enums\Unit;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResizeImage implements ShouldQueue
{
    use Queueable;

    // private: accessibili solo all'interno della classe memorizzano rispettivamente width, height, nome file immagine salvata, percorso directory immagine 
    private $w, $h, $fileName, $path;

    // funzione costruttore richiamata ogni volta che viene creata una nuova istanza della classe
    public function __construct($filePath, $w, $h) // percorso, larghezza e altezza desiderati per manipolazione immagine
    {
        $this->path = dirname($filePath); // estrae il percorso della directory dal path completo
        $this->fileName = basename($filePath); // estrae il nome del file con estensione
        $this->w = $w; // assegna valore dell'argomento $w alla proprietà $w della classe
        $this->h = $h;
    }

    public function handle(): void
    {
        // assegnano i valori delle proprietà $w e $h a variabili locali
        $w = $this->w;
        $h = $this->h;

        // costruisce percorso completo del file immagine originale
        $srcPath = storage_path() . '/app/public/' . $this->path . '/' . $this->fileName;

        // costruisce percorso completo di destinazione del file immagine ritagliata, aggiungendo 'crop_{w}x{h}_' al nome del file originale
        $destPath = storage_path() . '/app/public/' . $this->path . "/crop_{$w}x{$h}_" . $this->fileName;

        Image::load($srcPath) // utilizza la libreria Spatie Image per caricare l'immagine originale
            ->crop($w, $h, CropPosition::Center) // ritaglia l'immagine centrata con larghezza e altezza desiderate

            // aggiunge un watermark visibile sull'immagine croppata
            ->watermark(
                base_path('resources/img/prestowatermark.png'),
                width: 100,       
                height: 100,      
                paddingX: 5,     // distanza orizzontale dal bordo (in percentuale)
                paddingY: 5,     // distanza verticale dal bordo (in percentuale)
                paddingUnit: Unit::Percent // specifica che paddingX e Y sono espressi in percentuale
            )

            ->save($destPath); // salvataggio dell'immagine modificata nel percorso di destinazione
    }
}