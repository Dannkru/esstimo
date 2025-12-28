<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Support\Facades\Log;

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
        
        // Sprawdź czy są dane do zaimportowania z session
        if (session()->has('imported_estimate_data')) {
            $this->loadImportedData(session()->pull('imported_estimate_data'));
        }
    }
    
    private function loadImportedData($data)
    {
        try {
            // Przywróć zaznaczone usługi
            if (isset($data['selected_services']) && is_array($data['selected_services'])) {
                foreach ($data['selected_services'] as $serviceId => $isSelected) {
                    if ($isSelected && is_numeric($serviceId)) {
                        $this->selectedServices[(int)$serviceId] = true;
                    }
                }
            }

            // Przywróć ilości i ceny
            if (isset($data['services_data']) && is_array($data['services_data'])) {
                foreach ($data['services_data'] as $serviceId => $serviceData) {
                    if (is_numeric($serviceId) && is_array($serviceData)) {
                        $serviceId = (int)$serviceId;
                        $quantity = floatval($serviceData['quantity'] ?? 0);
                        $price = floatval($serviceData['price'] ?? 0);
                        
                        if ($quantity >= 0 && $quantity <= 1000000 && $price >= 0 && $price <= 1000000) {
                            $this->quantities[$serviceId] = $quantity;
                            $this->prices[$serviceId] = $price;
                        }
                    }
                }
            }

            // Przywróć category_slug
            if (isset($data['category_slug']) && is_string($data['category_slug'])) {
                $category = Category::where('slug', $data['category_slug'])
                    ->where('is_active', true)
                    ->first();
                
                if ($category) {
                    $this->categorySlug = $category->slug;
                    $this->selectedCategories[$category->slug] = true;
                    $this->expandedCategories[$category->slug] = true;
                }
            }

            // Przywróć expanded_categories
            if (isset($data['expanded_categories']) && is_array($data['expanded_categories'])) {
                $validCategories = Category::where('is_active', true)
                    ->pluck('slug')
                    ->toArray();
                
                foreach ($data['expanded_categories'] as $categorySlug => $isExpanded) {
                    if (in_array($categorySlug, $validCategories) && $isExpanded) {
                        $this->expandedCategories[$categorySlug] = true;
                        $this->selectedCategories[$categorySlug] = true;
                    }
                }
            }

            // Rozwiń wszystkie kategorie z zaznaczonymi usługami
            foreach ($this->selectedServices as $serviceId => $isSelected) {
                if ($isSelected) {
                    $service = Service::find($serviceId);
                    if ($service && $service->category) {
                        $category = $service->category;
                        if ($category->is_active) {
                            $this->expandedCategories[$category->slug] = true;
                            $this->selectedCategories[$category->slug] = true;
                        }
                    }
                }
            }

            $this->dispatch('show-success', message: 'Wycena została wczytana pomyślnie.');

        } catch (\Exception $e) {
            Log::error('Błąd ładowania zaimportowanych danych: ' . $e->getMessage());
            $this->dispatch('show-error', message: 'Błąd podczas ładowania wyceny.');
        }
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

    public function importEstimate($fileContent)
    {
        try {
            // 1. Ograniczenie rozmiaru pliku (1MB)
            if (strlen($fileContent) > 1048576) {
                $this->dispatch('show-error', message: 'Plik jest zbyt duży. Maksymalny rozmiar to 1MB.');
                return;
            }

            // 2. Dekodowanie JSON z obsługą błędów
            $data = json_decode($fileContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->dispatch('show-error', message: 'Nieprawidłowy format pliku JSON. ' . json_last_error_msg());
                return;
            }

            // 3. Walidacja struktury pliku
            if (!is_array($data)) {
                $this->dispatch('show-error', message: 'Nieprawidłowa struktura pliku.');
                return;
            }

            if (!isset($data['version']) || !isset($data['selected_services'])) {
                $this->dispatch('show-error', message: 'Plik nie zawiera wymaganych danych wyceny.');
                return;
            }

            // 4. Walidacja wersji (opcjonalnie, dla przyszłych wersji)
            if (isset($data['version']) && version_compare($data['version'], '1.0', '<')) {
                $this->dispatch('show-error', message: 'Nieobsługiwana wersja pliku. Wymagana wersja 1.0 lub nowsza.');
                return;
            }

            // 5. Walidacja i sanityzacja selected_services
            if (!is_array($data['selected_services'])) {
                $this->dispatch('show-error', message: 'Nieprawidłowy format danych usług.');
                return;
            }

            // 6. Sprawdzenie czy wszystkie ID usług istnieją w bazie
            $serviceIds = array_keys($data['selected_services']);
            $serviceIds = array_filter($serviceIds, fn($id) => is_numeric($id) && $id > 0);
            $serviceIds = array_map('intval', $serviceIds);

            if (empty($serviceIds)) {
                $this->dispatch('show-error', message: 'Brak prawidłowych usług w pliku.');
                return;
            }

            // Pobierz tylko aktywne usługi z bazy
            $validServices = Service::whereIn('id', $serviceIds)
                ->where('is_active', true)
                ->pluck('id')
                ->toArray();

            $invalidIds = array_diff($serviceIds, $validServices);
            if (!empty($invalidIds)) {
                // Usuń nieprawidłowe ID, ale kontynuuj import
                foreach ($invalidIds as $invalidId) {
                    unset($data['selected_services'][$invalidId]);
                    if (isset($data['services_data'][$invalidId])) {
                        unset($data['services_data'][$invalidId]);
                    }
                }
            }

            // 7. Walidacja i sanityzacja services_data
            if (isset($data['services_data']) && is_array($data['services_data'])) {
                foreach ($data['services_data'] as $serviceId => $serviceData) {
                    // Sprawdź czy serviceId jest prawidłowy
                    if (!in_array((int)$serviceId, $validServices)) {
                        continue; // Pomiń nieprawidłowe usługi
                    }

                    // Walidacja struktury danych usługi
                    if (!is_array($serviceData)) {
                        continue;
                    }

                    // Sanityzacja quantity
                    $quantity = isset($serviceData['quantity']) 
                        ? floatval($serviceData['quantity']) 
                        : 0;
                    
                    // Walidacja zakresu quantity (0 - 1,000,000)
                    if ($quantity < 0 || $quantity > 1000000) {
                        $this->dispatch('show-error', message: "Nieprawidłowa ilość dla usługi ID: {$serviceId}. Dozwolony zakres: 0 - 1,000,000.");
                        continue;
                    }

                    // Sanityzacja price
                    $price = isset($serviceData['price']) 
                        ? floatval($serviceData['price']) 
                        : 0;
                    
                    // Walidacja zakresu price (0 - 1,000,000)
                    if ($price < 0 || $price > 1000000) {
                        $this->dispatch('show-error', message: "Nieprawidłowa cena dla usługi ID: {$serviceId}. Dozwolony zakres: 0 - 1,000,000.");
                        continue;
                    }

                    // Przypisz zsanityzowane wartości
                    $this->quantities[$serviceId] = $quantity;
                    $this->prices[$serviceId] = $price;
                }
            }

            // 8. Przywróć zaznaczone usługi (tylko te, które są w bazie)
            $this->selectedServices = [];
            foreach ($validServices as $serviceId) {
                if (isset($data['selected_services'][$serviceId]) && $data['selected_services'][$serviceId]) {
                    $this->selectedServices[$serviceId] = true;
                }
            }

            // 9. Przywróć category_slug jeśli istnieje
            if (isset($data['category_slug']) && is_string($data['category_slug'])) {
                $category = Category::where('slug', $data['category_slug'])
                    ->where('is_active', true)
                    ->first();
                
                if ($category) {
                    $this->categorySlug = $category->slug;
                    $this->selectedCategories[$category->slug] = true;
                    $this->expandedCategories[$category->slug] = true;
                }
            }

            // 10. Przywróć expanded_categories (tylko dla istniejących kategorii)
            if (isset($data['expanded_categories']) && is_array($data['expanded_categories'])) {
                $validCategories = Category::where('is_active', true)
                    ->pluck('slug')
                    ->toArray();
                
                foreach ($data['expanded_categories'] as $categorySlug => $isExpanded) {
                    if (in_array($categorySlug, $validCategories) && $isExpanded) {
                        $this->expandedCategories[$categorySlug] = true;
                        $this->selectedCategories[$categorySlug] = true;
                    }
                }
            }

            // 11. Rozwiń wszystkie kategorie z zaznaczonymi usługami
            foreach ($this->selectedServices as $serviceId => $isSelected) {
                if ($isSelected) {
                    $service = Service::find($serviceId);
                    if ($service && $service->category) {
                        $category = $service->category;
                        if ($category->is_active) {
                            $this->expandedCategories[$category->slug] = true;
                            $this->selectedCategories[$category->slug] = true;
                        }
                    }
                }
            }

            $this->dispatch('show-success', message: 'Wycena została wczytana pomyślnie.');

        } catch (\Exception $e) {
            \Log::error('Błąd importu wyceny: ' . $e->getMessage());
            $this->dispatch('show-error', message: 'Błąd podczas wczytywania wyceny: ' . $e->getMessage());
        }
    }

    public function handleFileImport($event)
    {
        try {
            $file = $event->target->files[0] ?? null;
            
            if (!$file) {
                $this->dispatch('show-error', message: 'Nie wybrano pliku.');
                return;
            }

            // Walidacja typu pliku
            if ($file->getClientOriginalExtension() !== 'json') {
                $this->dispatch('show-error', message: 'Nieprawidłowy typ pliku. Wymagany format: .json');
                return;
            }

            // Walidacja rozmiaru pliku przed wczytaniem
            if ($file->getSize() > 1048576) {
                $this->dispatch('show-error', message: 'Plik jest zbyt duży. Maksymalny rozmiar to 1MB.');
                return;
            }

            $content = file_get_contents($file->getRealPath());
            
            if ($content === false) {
                $this->dispatch('show-error', message: 'Nie można odczytać pliku.');
                return;
            }

            $this->importEstimate($content);

        } catch (\Exception $e) {
            \Log::error('Błąd obsługi pliku: ' . $e->getMessage());
            $this->dispatch('show-error', message: 'Błąd podczas przetwarzania pliku: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.calculator', [
            'categories' => $this->categories,
        ]);
    }
}
