<?php

namespace App\Jobs;

use App\Models\Image;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Enums\AlignPosition;
use Spatie\Image\Image as SpatieImage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;

class RemoveFaces implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // ID dell'immagine associata all'articolo da analizzare
    private $article_image_id;

    // Costruttore: salva l'ID passato al job
    public function __construct($article_image_id)
    {
        $this->article_image_id = $article_image_id;
    }

    // Metodo principale del job: rileva e censura i volti
    public function handle(): void
    {
        // Recupera l'immagine dal DB
        $i = Image::find($this->article_image_id);
        if (!$i || !$i->path) {
            \Log::error('RemoveFaces: immagine non trovata o path vuoto per ID ' . $this->article_image_id);
            return;
        }

        // Ottiene il path completo del file immagine
        $srcPath = storage_path('app/public/' . $i->path); 

        // Carica il contenuto binario dell'immagine
        $image = file_get_contents($srcPath);

        // Imposta le credenziali per l'API Google Vision
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . base_path('google_credential.json'));

        // Inizializza il client Vision e avvia la rilevazione volti
        $imageAnnotator = new ImageAnnotatorClient();
        $response = $imageAnnotator->faceDetection($image); 
        $faces = $response->getFaceAnnotations();

        // Per ogni volto rilevato, applica la censura
        foreach ($faces as $face) {
            $vertices = $face->getBoundingPoly()->getVertices();

            $bounds = []; 

            // Ricava le coordinate del riquadro facciale
            foreach ($vertices as $vertex) {
                $bounds[] = [$vertex->getX(), $vertex->getY()];
            }

            $w = $bounds[2][0] - $bounds[0][0];
            $h = $bounds[2][1] - $bounds[0][1];

            // Carica l'immagine con Spatie Image per applicare watermark/censura
            $image = SpatieImage::load($srcPath);

            $image->watermark(
                base_path('resources/img/stellinacensura.png'), // Immagine di censura
                AlignPosition::TopLeft,
                paddingX: $bounds[0][0],
                paddingY: $bounds[0][1],
                width: $w,
                height: $h,
                fit: Fit::Stretch
            );

            // Sovrascrive l'immagine originale con quella modificata
            $image->save($srcPath);
        }

        // Chiude il client Google Vision
        $imageAnnotator->close();
    }
}
