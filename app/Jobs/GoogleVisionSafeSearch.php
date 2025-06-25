<?php

namespace App\Jobs;

use App\Models\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;

class GoogleVisionSafeSearch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Proprietà privata per memorizzare l'ID dell'immagine
    private $article_image_id;

    // Costruttore per ricevere l'ID dell'immagine da processare
    public function __construct($article_image_id)
    {
        $this->article_image_id = $article_image_id;
    }

    // Metodo eseguito quando il job viene processato dalla coda
    public function handle(): void
    {
        // Recupera l'immagine dal database usando l'ID
        $i = Image::find($this->article_image_id);
        if (!$i) {
            return; // Se non trovata, termina il job
        }

        // Recupera il contenuto binario dell'immagine dal filesystem
        $image = file_get_contents(storage_path('app/public/' . $i->path));

        // Imposta la variabile di ambiente per le credenziali di Google Cloud
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . base_path(env('GOOGLE_APPLICATION_CREDENTIALS')));


        // Istanzia il client Google Vision e invia l'immagine per Safe Search Detection
        $imageAnnotator = new ImageAnnotatorClient();
        $response = $imageAnnotator->safeSearchDetection($image);
        $imageAnnotator->close(); // Chiude il client

        // Ottiene le annotazioni di sicurezza dalla risposta
        $safe = $response->getSafeSearchAnnotation();

        // Estrae i diversi livelli di contenuto sensibile
        $adult = $safe->getAdult();
        $medical = $safe->getMedical();
        $spoof = $safe->getSpoof();
        $violence = $safe->getViolence();
        $racy = $safe->getRacy();

        // Mappa dei livelli di sicurezza con le rispettive classi Bootstrap per lo styling
        $likelihoodName = [
            'text-secondary bi bi-circle-fill',
            'text-success bi bi-check-circle-fill',
            'text-success bi bi-check-circle-fill',
            'text-warning bi bi-exclamation-circle-fill',
            'text-warning bi bi-exclamation-circle-fill',
            'text-danger bi bi-dash-circle-fill'
        ];

        // Assegna i valori nel medesimo ordine definito nella migration
        $i->adult = $likelihoodName[$adult];
        $i->spoof = $likelihoodName[$spoof];
        $i->medical = $likelihoodName[$medical];
        $i->violence = $likelihoodName[$violence];
        $i->racy = $likelihoodName[$racy];

        // Salva le modifiche al modello nel database
        $i->save();
    }
}