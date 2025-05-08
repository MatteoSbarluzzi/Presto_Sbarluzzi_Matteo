<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use Illuminate\Database\Seeder;

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
        foreach ($this->categories as $category){
            Category::create([
                'name' => $category
            ]);
        }
    }
}
