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
        'Motori',
        'Prodotti tipici',
        'Agricoltura',
        'Artigianato',
        'Turismo',
        'Esperienze',
        'Lavoro',
        'Cultura',
        'Arte',

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
