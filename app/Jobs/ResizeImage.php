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

    // Private: accessibili solo all'interno della classe memorizzano rispettivamente width, height, nome file immagine salvata, percorso directory immagine 
    private $w, $h, $fileName, $path;

    // Funzione costruttore richiamata ogni volta che viene creata una nuova istanza della classe
    public function __construct($filePath, $w, $h) // percorso, larghezza e altezza desiderati per manipolazione immagine
    {
        $this->path = dirname($filePath); // Estrae il percorso della directory dal path completo
        $this->fileName = basename($filePath); // Estrae il nome del file con estensione
        $this->w = $w; 
        $this->h = $h;
    }

    public function handle(): void
    {
      
        $w = $this->w;
        $h = $this->h;

        // Costruisce percorso completo del file immagine originale
        $srcPath = storage_path() . '/app/public/' . $this->path . '/' . $this->fileName;

        // Costruisce percorso completo di destinazione del file immagine ritagliata, aggiungendo 'crop_{w}x{h}_' al nome del file originale
        $destPath = storage_path() . '/app/public/' . $this->path . "/crop_{$w}x{$h}_" . $this->fileName;

        Image::load($srcPath) // Utilizza la libreria Spatie Image per caricare l'immagine originale
            ->crop($w, $h, CropPosition::Center) // Ritaglia l'immagine centrata con larghezza e altezza desiderate

            // Aggiunge un watermark visibile sull'immagine croppata
            ->watermark(
                base_path('resources/img/prestowatermark.png'),
                width: 100,       
                height: 100,      
                paddingX: 5,    
                paddingY: 5,     
                paddingUnit: Unit::Percent 
            )

            ->save($destPath); 
    }
}