<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public $categories = [
        'Immobili',
        'Elettronica',
        'Auto e Moto',
        'Abbigliamento',
        'Sport e Tempo Libero',
        'Videogiochi',
        'Giardinaggio',
        'Animali domestici',
        'Casa',
        'Libri e Riviste'
    ];

    public function run(): void
    {
        foreach ($this->categories as $categoryName){
            Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName, '_'), 
            ]);
        }
    }
}
