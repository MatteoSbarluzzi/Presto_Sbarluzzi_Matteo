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

    private $article_image_id;

    public function __construct($article_image_id)
    {
        $this->article_image_id = $article_image_id;
    }

    public function handle(): void
    {
        $i = Image::find($this->article_image_id);
        if (!$i || !$i->path) {
            \Log::error('RemoveFaces: immagine non trovata o path vuoto per ID ' . $this->article_image_id);
            return;
        }

        $srcPath = storage_path('app/public/' . $i->path); // usiamo il campo corretto "path" invece di "patch"
        $image = file_get_contents($srcPath);

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . base_path(env('GOOGLE_APPLICATION_CREDENTIALS')));


        $imageAnnotator = new ImageAnnotatorClient();
        $response = $imageAnnotator->faceDetection($image); //faceDetection parte sulle immagini per rilevare i volti
        $faces = $response->getFaceAnnotations();//sul risultato del precedente parte questo che serve a recuperare informazioni sui volti individuati nell'immagine, che restituisce un campo ripetuto contenente una lista di oggetti FaceAnnotation. Il risultato è salvato in $faces.

        foreach ($faces as $face){//stiamo iterando sui volti rilevati e all'interno del ciclo il codice estrae le coordinate dei vertici del bounding box (modo per definire posizione e dimensioni di un oggetto in un'immagine) che circonda ogni volto
            $vertices = $face->getBoundingPoly()->getVertices();

            $bounds = []; //array che memorizza coordinate dei vertici

            foreach ($vertices as $vertex){//calcola larghezza e altezza del bounding box del volto
                $bounds[] = [$vertex->getX(), $vertex->getY()];
            }

            $w = $bounds[2][0] - $bounds[0][0];
            $h = $bounds[2][1] - $bounds[0][1];

            //carichiamo l'immagine usando la libreria Spatie Image per poter effettuare la censura dei volti
            $image = SpatieImage::load($srcPath);

            //metodo watermark per sovrapporre a ogni volto rilevato un'immagine di censura da noi scelta
            $image->watermark(
                base_path('resources/img/stellinacensura.png'),
                AlignPosition::TopLeft,
                //padding specificano lo spostamento orizzontale e verticale della sovrapposizione rispetto all'angolo superiore sinistro del bounding box
                paddingX: $bounds[0][0],
                paddingY: $bounds[0][1],
                //definiscono la larghezza e l'altezza dell'immagine di sovrapposizione, forzata ad adattarsi alle dimensioni del bounding box con Fit::Stretch
                width: $w,
                height: $h,
                fit: Fit::Stretch
            );

            //salviamo l'immagine modificata con la sovrapposizione sovrascritta sul percorso originale 
            $image->save($srcPath);
        }

        $imageAnnotator->close();
    }
}