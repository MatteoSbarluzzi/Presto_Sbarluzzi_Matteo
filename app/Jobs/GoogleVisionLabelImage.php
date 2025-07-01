<?php

namespace App\Jobs;

use App\Models\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;

class GoogleVisionLabelImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // ID dell'immagine da processare
    private $article_image_id;

    // Costruttore del job: riceve l'ID immagine e lo salva nella proprietà
    public function __construct($article_image_id)
    {
        $this->article_image_id = $article_image_id;
    }

    // Metodo principale che viene eseguito quando il job viene processato dalla coda
    public function handle(): void
    {
        // Recupera l'immagine dal database
        $i = Image::find($this->article_image_id);
        if (!$i) {
            return; // Se l'immagine non esiste, interrompe il job
        }

        // Recupera il contenuto dell'immagine dal filesystem
        $image = file_get_contents(storage_path('app/public/' . $i->path));

        // Imposta la variabile di ambiente per l'autenticazione con Google Cloud
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . base_path('google_credential.json'));


        // Crea il client Vision per l'analisi delle immagini
        $imageAnnotator = new ImageAnnotatorClient();

        // Richiede a Google l'analisi delle etichette dell'immagine
        $response = $imageAnnotator->labelDetection($image);

        // Recupera le annotazioni delle etichette dalla risposta
        $labels = $response->getLabelAnnotations();

        // Se sono presenti etichette, estraiamo le descrizioni in un array
        if ($labels) {
            $result = [];

            foreach ($labels as $label) {
                $result[] = $label->getDescription(); // Aggiunge la descrizione dell'etichetta
            }

            // Salva l'array delle etichette nel campo 'labels' dell'immagine
            $i->labels = $result;
            $i->save(); 
        }

        // Chiude il client Google Vision
        $imageAnnotator->close();
    }
}