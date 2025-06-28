<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Metodo per eliminare un'immagine sia dallo storage che dal database.
     * Questo viene invocato tramite una richiesta AJAX.
     */
    public function destroy(Image $image)
    {
        // Elimina fisicamente l'immagine dallo storage
        Storage::delete($image->path);

        // Elimina il record dal database
        $image->delete();

        // Risposta JSON per il frontend
        return response()->json(['success' => true]);
    }
}
