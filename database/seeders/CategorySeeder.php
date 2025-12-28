<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Prace Malarskie',
                'slug' => 'malowanie',
                'description' => 'Malowanie ścian, gruntowanie, tapetowanie',
                'icon' => '🎨',
                'color' => 'indigo',
                'order' => 1,
            ],
            [
                'name' => 'Prace Glazurnicze',
                'slug' => 'glazura',
                'description' => 'Układanie płytek, fugowanie, montaż',
                'icon' => '🧱',
                'color' => 'yellow',
                'order' => 2,
            ],
            [
                'name' => 'Prace Elektryczne',
                'slug' => 'elektryka',
                'description' => 'Punkty, gniazdka, montaż lamp i LED',
                'icon' => '⚡',
                'color' => 'amber',
                'order' => 3,
            ],
            [
                'name' => 'Prace Hydrauliczne',
                'slug' => 'hydraulika',
                'description' => 'Montaż armatury, podłączenia, naprawy',
                'icon' => '🚿',
                'color' => 'blue',
                'order' => 4,
            ],
            [
                'name' => 'Sucha Zabudowa',
                'slug' => 'sucha-zabudowa',
                'description' => 'Montaż płyt G-K, sufity podwieszane',
                'icon' => '🏗️',
                'color' => 'gray',
                'order' => 5,
            ],
            [
                'name' => 'Prace Stolarskie',
                'slug' => 'stolarka',
                'description' => 'Montaż mebli, szaf, drzwi i okien',
                'icon' => '🪚',
                'color' => 'emerald',
                'order' => 6,
            ],
            [
                'name' => 'Glazura w łazience/Armatura',
                'slug' => 'glazura-w-lazience-armatura',
                'description' => 'Układanie płytek ściennych w łazience, montaż armatury',
                'icon' => '🚿',
                'color' => 'blue',
                'order' => 7,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
