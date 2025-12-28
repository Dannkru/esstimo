<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Category;
use App\Models\Service;

#[Layout('layouts.app')]
class Calculator extends Component
{
    public $categorySlug = null;
    public $selectedCategories = [];
    public $selectedServices = [];
    public $quantities = [];
    public $prices = [];
    public $expandedCategories = [];
    public $hasScrolledToCategory = false;

    public function mount($category = null)
    {
        if ($category) {
            $this->categorySlug = $category;
            $this->selectedCategories[$category] = true;
            $this->expandedCategories[$category] = true;
        }
        $this->initializeAllServices();
    }

    public function scrollToCategory()
    {
        if ($this->categorySlug) {
            $this->dispatch('scroll-to-category', category: $this->categorySlug);
        }
    }

    private function getCategoryName($slug)
    {
        $category = Category::where('slug', $slug)->first();
        return $category ? $category->name : ucfirst(str_replace('-', ' ', $slug));
    }

    private function initializeAllServices()
    {
        $services = Service::where('is_active', true)->get();
        
        foreach ($services as $service) {
            $this->quantities[$service->id] = '';
            $this->prices[$service->id] = $service->suggested_price;
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
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        $allServices = [];
        
        foreach ($categories as $category) {
            $services = $category->services;
            $allServices[$category->slug] = $services->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'unit' => $service->unit,
                    'suggested_price' => (float) $service->suggested_price,
                ];
            })->toArray();
        }
        
        return $allServices;
    }

    public function getServicesForCategory($categorySlug = null)
    {
        $slug = $categorySlug ?? $this->categorySlug;
        if (!$slug) {
            return [];
        }
        
        $category = Category::where('slug', $slug)->where('is_active', true)->first();
        if (!$category) {
            return [];
        }
        
        return $category->services->map(function ($service) {
            return [
                'id' => $service->id,
                'name' => $service->name,
                'unit' => $service->unit,
                'suggested_price' => (float) $service->suggested_price,
            ];
        })->toArray();
    }

    public function getCategoriesProperty()
    {
        return Category::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'icon' => $category->icon,
                    'color' => $category->color,
                ];
            })
            ->toArray();
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
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        $grouped = [];
        
        foreach ($categories as $category) {
            $categoryServices = [];
            
            foreach ($category->services as $service) {
                if (isset($this->selectedServices[$service->id]) && $this->selectedServices[$service->id]) {
                    $quantity = floatval($this->quantities[$service->id] ?? 0);
                    $price = floatval($this->prices[$service->id] ?? 0);
                    
                    if ($quantity > 0 && $price > 0) {
                        $categoryServices[] = [
                            'id' => $service->id,
                            'name' => $service->name,
                            'unit' => $service->unit,
                            'quantity' => $quantity,
                            'price' => $price,
                            'total' => $quantity * $price,
                        ];
                    }
                }
            }
            
            if (count($categoryServices) > 0) {
                $grouped[] = [
                    'category' => $category->name,
                    'slug' => $category->slug,
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

    public function exportEstimate()
    {
        // Przygotuj dane do eksportu - tylko zaznaczone usługi z wartościami
        $exportData = [
            'version' => '1.0',
            'exported_at' => now()->toIso8601String(),
            'category_slug' => $this->categorySlug,
            'selected_categories' => $this->selectedCategories,
            'expanded_categories' => $this->expandedCategories,
            'selected_services' => [],
            'services_data' => [],
        ];

        // Zbierz tylko zaznaczone usługi z ilościami i cenami
        foreach ($this->selectedServices as $serviceId => $isSelected) {
            if ($isSelected) {
                $quantity = floatval($this->quantities[$serviceId] ?? 0);
                $price = floatval($this->prices[$serviceId] ?? 0);
                
                if ($quantity > 0 && $price > 0) {
                    $exportData['selected_services'][$serviceId] = true;
                    $exportData['services_data'][$serviceId] = [
                        'quantity' => $quantity,
                        'price' => $price,
                    ];
                }
            }
        }

        $filename = 'wycena-estimo-' . now()->format('Y-m-d-His') . '.json';

        return response()->streamDownload(function () use ($exportData) {
            echo json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function render()
    {
        return view('livewire.calculator', [
            'categories' => $this->categories,
        ]);
    }
}
