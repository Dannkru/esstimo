<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Calculator extends Component
{
    public $categorySlug;
    public $categoryName;

    public function mount($category)
    {
        $this->categorySlug = $category;
        $this->categoryName = $this->getCategoryName($category);
    }

    private function getCategoryName($slug)
    {
        $categories = [
            'malowanie' => 'Prace Malarskie',
            'glazura' => 'Prace Glazurnicze',
            'elektryka' => 'Prace Elektryczne',
            'hydraulika' => 'Prace Hydrauliczne',
            'sucha-zabudowa' => 'Sucha Zabudowa',
            'stolarka' => 'Prace Stolarskie',
        ];

        return $categories[$slug] ?? ucfirst(str_replace('-', ' ', $slug));
    }

    public function render()
    {
        return view('livewire.calculator');
    }
}
