<?php

namespace App\Jobs;

use Spatie\Image\Image;
use Spatie\Image\Enums\CropPosition;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResizeImage implements ShouldQueue
{
    use Queueable;

    //private accessibili solo all'interno della classe
    //memorizzano rispettivamente width, height, nome file di immagine salvata, percorso directory immagine 
    private $w, $h, $fileName, $path;
    //funzione costruttore richiamata ogni volta che viene creata una nuova istanza della classe
    public function __construct($filePath, $w, $h)//percorso, larghezza e altezza desiderati per manipolazione immagine
    {
        $this->path = dirname($filePath); //estrae il percorso della directory estratto dall'argomento $filePath
        $this->fileName = basename($filePath); //estrae il nome del file...
        $this->w = $w; //assegna valore dell'argomento $w alla proprietà $w della classe
        $this->h = $h;
    }


    public function handle(): void
    {   
        //assegnano i valori delle proprieta $w e $h, memorizzando larghezza e altezza dell'immagine
        $w = $this->w;
        $h = $this->h;
        //costruisce percorso completo del file immagine originale
        $srcPath = storage_path() . '/app/public/' . $this->path . '/' . $this->fileName;
        //costruisce percorso completo di destinazione del file immagine ritagliata, simile al precedente, ma aggiunge il crop prima del nome del file originale per indicare dimensione ritaglio; le graffe attorno $w e $h consentono interpolazione variabili per creare dinamicamente nome file
        $destPath = storage_path() . '/app/public/' . $this->path . "/crop_{$w}x{$h}_" . $this->fileName;

        Image::load($srcPath)//utilizza la facciata Image fornita dalla libreria Spatie/Image per caricare l'immagine originale dal percorso $srcPath
            ->crop($w, $h, CropPosition::Center) //ritaglia immagine caricata
            ->save($destPath); //salvataggio dell'immagine ritagliata nel percorso di destinazione
    }
}
