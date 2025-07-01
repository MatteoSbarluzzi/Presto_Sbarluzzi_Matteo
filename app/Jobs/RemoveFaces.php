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

        $srcPath = storage_path('app/public/' . $i->path); 
        $image = file_get_contents($srcPath);

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . base_path('google_credential.json'));


        $imageAnnotator = new ImageAnnotatorClient();
        $response = $imageAnnotator->faceDetection($image); 
        $faces = $response->getFaceAnnotations();

        foreach ($faces as $face){
            $vertices = $face->getBoundingPoly()->getVertices();

            $bounds = []; 

            foreach ($vertices as $vertex){
                $bounds[] = [$vertex->getX(), $vertex->getY()];
            }

            $w = $bounds[2][0] - $bounds[0][0];
            $h = $bounds[2][1] - $bounds[0][1];

            //carichiamo l'immagine usando la libreria Spatie Image per poter effettuare la censura dei volti
            $image = SpatieImage::load($srcPath);


            $image->watermark(
                base_path('resources/img/stellinacensura.png'),
                AlignPosition::TopLeft,
             
                paddingX: $bounds[0][0],
                paddingY: $bounds[0][1],
          
                width: $w,
                height: $h,
                fit: Fit::Stretch
            );


            $image->save($srcPath);
        }

        $imageAnnotator->close();
    }
}