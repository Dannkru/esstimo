<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Calculator extends Component
{
    public $categorySlug = null;
    public $selectedCategories = [];
    public $selectedServices = [];
    public $quantities = [];
    public $prices = [];
    public $expandedCategories = [];

    public function mount($category = null)
    {
        if ($category) {
            $this->categorySlug = $category;
            $this->selectedCategories[$category] = true;
            $this->expandedCategories[$category] = true;
        }
        $this->initializeAllServices();
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

    private function initializeAllServices()
    {
        $allServices = $this->getAllServices();
        
        foreach ($allServices as $categorySlug => $services) {
            foreach ($services as $service) {
                $this->quantities[$service['id']] = '';
                $this->prices[$service['id']] = $service['suggested_price'];
            }
        }
    }

    public function toggleCategory($categorySlug)
    {
        if (!isset($this->selectedCategories[$categorySlug])) {
            $this->selectedCategories[$categorySlug] = true;
        }
        $this->expandedCategories[$categorySlug] = !($this->expandedCategories[$categorySlug] ?? false);
    }

    public function getAllServices()
    {
        return [
            'malowanie' => [
                ['id' => 1, 'name' => 'Dwukrotne szpachlowanie ścian', 'unit' => 'm²', 'suggested_price' => 18.00],
                ['id' => 2, 'name' => 'Szlifowanie ścian', 'unit' => 'm²', 'suggested_price' => 12.00],
                ['id' => 3, 'name' => 'Gruntowanie ścian', 'unit' => 'm²', 'suggested_price' => 8.00],
                ['id' => 4, 'name' => 'Malowanie dwukrotne farbą lateksową', 'unit' => 'm²', 'suggested_price' => 25.00],
                ['id' => 5, 'name' => 'Malowanie dwukrotne farbą akrylową', 'unit' => 'm²', 'suggested_price' => 22.00],
                ['id' => 6, 'name' => 'Malowanie sufitów', 'unit' => 'm²', 'suggested_price' => 20.00],
                ['id' => 7, 'name' => 'Tapetowanie ścian', 'unit' => 'm²', 'suggested_price' => 35.00],
                ['id' => 8, 'name' => 'Malowanie drzwi', 'unit' => 'szt', 'suggested_price' => 80.00],
                ['id' => 9, 'name' => 'Malowanie okien', 'unit' => 'szt', 'suggested_price' => 100.00],
                ['id' => 10, 'name' => 'Malowanie listew przypodłogowych', 'unit' => 'mb', 'suggested_price' => 8.00],
            ],
            'glazura' => [
                ['id' => 11, 'name' => 'Układanie płytek ściennych (format do 30x30)', 'unit' => 'm²', 'suggested_price' => 45.00],
                ['id' => 12, 'name' => 'Układanie płytek ściennych (format powyżej 30x30)', 'unit' => 'm²', 'suggested_price' => 55.00],
                ['id' => 13, 'name' => 'Układanie płytek podłogowych (format do 30x30)', 'unit' => 'm²', 'suggested_price' => 50.00],
                ['id' => 14, 'name' => 'Układanie płytek podłogowych (format powyżej 30x30)', 'unit' => 'm²', 'suggested_price' => 60.00],
                ['id' => 15, 'name' => 'Układanie płytek w łazience', 'unit' => 'm²', 'suggested_price' => 65.00],
                ['id' => 16, 'name' => 'Układanie płytek w kuchni', 'unit' => 'm²', 'suggested_price' => 60.00],
                ['id' => 17, 'name' => 'Fugowanie płytek', 'unit' => 'm²', 'suggested_price' => 15.00],
                ['id' => 18, 'name' => 'Cięcie płytek', 'unit' => 'mb', 'suggested_price' => 20.00],
                ['id' => 19, 'name' => 'Montaż listwy narożnej', 'unit' => 'mb', 'suggested_price' => 25.00],
                ['id' => 20, 'name' => 'Montaż cokołów', 'unit' => 'mb', 'suggested_price' => 30.00],
            ],
            'elektryka' => [
                ['id' => 21, 'name' => 'Montaż gniazdka jednofazowego', 'unit' => 'szt', 'suggested_price' => 80.00],
                ['id' => 22, 'name' => 'Montaż gniazdka trójfazowego', 'unit' => 'szt', 'suggested_price' => 120.00],
                ['id' => 23, 'name' => 'Montaż włącznika światła', 'unit' => 'szt', 'suggested_price' => 60.00],
                ['id' => 24, 'name' => 'Montaż punktu świetlnego', 'unit' => 'szt', 'suggested_price' => 100.00],
                ['id' => 25, 'name' => 'Montaż lampy sufitowej', 'unit' => 'szt', 'suggested_price' => 120.00],
                ['id' => 26, 'name' => 'Montaż lampy ściennej', 'unit' => 'szt', 'suggested_price' => 90.00],
                ['id' => 27, 'name' => 'Montaż taśmy LED', 'unit' => 'mb', 'suggested_price' => 40.00],
                ['id' => 28, 'name' => 'Montaż listwy LED', 'unit' => 'mb', 'suggested_price' => 50.00],
                ['id' => 29, 'name' => 'Montaż oprawy LED w suficie', 'unit' => 'szt', 'suggested_price' => 80.00],
                ['id' => 30, 'name' => 'Montaż dzwonka do drzwi', 'unit' => 'szt', 'suggested_price' => 150.00],
            ],
            'hydraulika' => [
                ['id' => 31, 'name' => 'Montaż umywalki', 'unit' => 'szt', 'suggested_price' => 200.00],
                ['id' => 32, 'name' => 'Montaż baterii umywalkowej', 'unit' => 'szt', 'suggested_price' => 150.00],
                ['id' => 33, 'name' => 'Montaż wanny', 'unit' => 'szt', 'suggested_price' => 400.00],
                ['id' => 34, 'name' => 'Montaż kabiny prysznicowej', 'unit' => 'szt', 'suggested_price' => 350.00],
                ['id' => 35, 'name' => 'Montaż baterii prysznicowej', 'unit' => 'szt', 'suggested_price' => 180.00],
                ['id' => 36, 'name' => 'Montaż toalety', 'unit' => 'szt', 'suggested_price' => 300.00],
                ['id' => 37, 'name' => 'Podłączenie pralki', 'unit' => 'szt', 'suggested_price' => 180.00],
                ['id' => 38, 'name' => 'Podłączenie zmywarki', 'unit' => 'szt', 'suggested_price' => 180.00],
                ['id' => 39, 'name' => 'Montaż grzejnika łazienkowego', 'unit' => 'szt', 'suggested_price' => 250.00],
                ['id' => 40, 'name' => 'Montaż syfonu', 'unit' => 'szt', 'suggested_price' => 100.00],
            ],
            'sucha-zabudowa' => [
                ['id' => 41, 'name' => 'Montaż ścian z płyt G-K (jedna warstwa)', 'unit' => 'm²', 'suggested_price' => 40.00],
                ['id' => 42, 'name' => 'Montaż ścian z płyt G-K (dwie warstwy)', 'unit' => 'm²', 'suggested_price' => 70.00],
                ['id' => 43, 'name' => 'Montaż sufitów podwieszanych', 'unit' => 'm²', 'suggested_price' => 35.00],
                ['id' => 44, 'name' => 'Montaż sufitów podwieszanych z oświetleniem', 'unit' => 'm²', 'suggested_price' => 50.00],
                ['id' => 45, 'name' => 'Montaż listew maskujących', 'unit' => 'mb', 'suggested_price' => 15.00],
                ['id' => 46, 'name' => 'Montaż profili stalowych', 'unit' => 'mb', 'suggested_price' => 12.00],
                ['id' => 47, 'name' => 'Montaż płyt G-K na suficie', 'unit' => 'm²', 'suggested_price' => 45.00],
                ['id' => 48, 'name' => 'Montaż płyt G-K na ścianach', 'unit' => 'm²', 'suggested_price' => 40.00],
                ['id' => 49, 'name' => 'Montaż narożników', 'unit' => 'mb', 'suggested_price' => 20.00],
                ['id' => 50, 'name' => 'Montaż ścianki działowej', 'unit' => 'm²', 'suggested_price' => 55.00],
            ],
            'stolarka' => [
                ['id' => 51, 'name' => 'Montaż szafy wnękowej', 'unit' => 'szt', 'suggested_price' => 300.00],
                ['id' => 52, 'name' => 'Montaż szafy przesuwnej', 'unit' => 'szt', 'suggested_price' => 400.00],
                ['id' => 53, 'name' => 'Montaż drzwi wewnętrznych', 'unit' => 'szt', 'suggested_price' => 200.00],
                ['id' => 54, 'name' => 'Montaż drzwi zewnętrznych', 'unit' => 'szt', 'suggested_price' => 350.00],
                ['id' => 55, 'name' => 'Montaż okien PCV', 'unit' => 'szt', 'suggested_price' => 250.00],
                ['id' => 56, 'name' => 'Montaż okien drewnianych', 'unit' => 'szt', 'suggested_price' => 300.00],
                ['id' => 57, 'name' => 'Montaż parapetów wewnętrznych', 'unit' => 'mb', 'suggested_price' => 80.00],
                ['id' => 58, 'name' => 'Montaż parapetów zewnętrznych', 'unit' => 'mb', 'suggested_price' => 120.00],
                ['id' => 59, 'name' => 'Montaż szafek kuchennych', 'unit' => 'mb', 'suggested_price' => 150.00],
                ['id' => 60, 'name' => 'Montaż blatu kuchennego', 'unit' => 'mb', 'suggested_price' => 200.00],
            ],
        ];

        return $allServices;
    }

    public function getServicesForCategory($categorySlug = null)
    {
        $allServices = $this->getAllServices();
        $slug = $categorySlug ?? $this->categorySlug;
        return $allServices[$slug] ?? [];
    }

    public function getCategoriesProperty()
    {
        return [
            [
                'name' => 'Prace Malarskie',
                'slug' => 'malowanie',
                'icon' => '🎨',
                'color' => 'indigo',
            ],
            [
                'name' => 'Prace Glazurnicze',
                'slug' => 'glazura',
                'icon' => '🧱',
                'color' => 'yellow',
            ],
            [
                'name' => 'Prace Elektryczne',
                'slug' => 'elektryka',
                'icon' => '⚡',
                'color' => 'amber',
            ],
            [
                'name' => 'Prace Hydrauliczne',
                'slug' => 'hydraulika',
                'icon' => '🚿',
                'color' => 'blue',
            ],
            [
                'name' => 'Sucha Zabudowa',
                'slug' => 'sucha-zabudowa',
                'icon' => '🏗️',
                'color' => 'gray',
            ],
            [
                'name' => 'Prace Stolarskie',
                'slug' => 'stolarka',
                'icon' => '🪚',
                'color' => 'emerald',
            ],
        ];
    }

    public function getTotalProperty()
    {
        $total = 0;
        
        foreach ($this->selectedServices as $serviceId => $isSelected) {
            if ($isSelected && isset($this->quantities[$serviceId]) && isset($this->prices[$serviceId])) {
                $quantity = floatval($this->quantities[$serviceId] ?? 0);
                $price = floatval($this->prices[$serviceId] ?? 0);
                
                if ($quantity > 0 && $price > 0) {
                    $total += $quantity * $price;
                }
            }
        }
        
        return $total;
    }

    public function getServiceTotal($serviceId)
    {
        if (!isset($this->selectedServices[$serviceId]) || !$this->selectedServices[$serviceId]) {
            return 0;
        }
        
        $quantity = floatval($this->quantities[$serviceId] ?? 0);
        $price = floatval($this->prices[$serviceId] ?? 0);
        
        return $quantity > 0 && $price > 0 ? $quantity * $price : 0;
    }

    public function getSelectedServicesForPrintProperty()
    {
        $allServices = $this->getAllServices();
        $grouped = [];
        
        foreach ($allServices as $categorySlug => $services) {
            $categoryServices = [];
            
            foreach ($services as $service) {
                if (isset($this->selectedServices[$service['id']]) && $this->selectedServices[$service['id']]) {
                    $quantity = floatval($this->quantities[$service['id']] ?? 0);
                    $price = floatval($this->prices[$service['id']] ?? 0);
                    
                    if ($quantity > 0 && $price > 0) {
                        $service['quantity'] = $quantity;
                        $service['price'] = $price;
                        $service['total'] = $quantity * $price;
                        $categoryServices[] = $service;
                    }
                }
            }
            
            if (count($categoryServices) > 0) {
                $categoryName = $this->getCategoryName($categorySlug);
                $grouped[] = [
                    'category' => $categoryName,
                    'slug' => $categorySlug,
                    'services' => $categoryServices,
                ];
            }
        }
        
        return $grouped;
    }

    public function printEstimate()
    {
        // Trigger print
        $this->dispatch('print-estimate');
    }

    public function render()
    {
        return view('livewire.calculator', [
            'categories' => $this->categories,
        ]);
    }
}
