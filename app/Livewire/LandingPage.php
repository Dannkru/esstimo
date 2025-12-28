<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Support\Facades\Log;

#[Layout('layouts.app')]
class LandingPage extends Component
{
    use WithFileUploads;
    public function getCategoriesProperty()
    {
        return Category::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'icon' => $category->icon,
                    'color' => $category->color,
                ];
            })
            ->toArray();
    }

    public function getColorClassesProperty()
    {
        return [
            'indigo' => [
                'border' => 'hover:border-indigo-500 dark:hover:border-indigo-400',
                'bg' => 'bg-indigo-100 dark:bg-indigo-900/30',
                'gradient' => 'from-indigo-50 to-transparent dark:from-indigo-900/20',
                'text' => 'text-indigo-600 dark:text-indigo-400',
                'hoverText' => 'group-hover:text-indigo-600 dark:group-hover:text-indigo-400',
            ],
            'yellow' => [
                'border' => 'hover:border-yellow-500 dark:hover:border-yellow-400',
                'bg' => 'bg-yellow-100 dark:bg-yellow-900/30',
                'gradient' => 'from-yellow-50 to-transparent dark:from-yellow-900/20',
                'text' => 'text-yellow-600 dark:text-yellow-400',
                'hoverText' => 'group-hover:text-yellow-600 dark:group-hover:text-yellow-400',
            ],
            'amber' => [
                'border' => 'hover:border-amber-500 dark:hover:border-amber-400',
                'bg' => 'bg-amber-100 dark:bg-amber-900/30',
                'gradient' => 'from-amber-50 to-transparent dark:from-amber-900/20',
                'text' => 'text-amber-600 dark:text-amber-400',
                'hoverText' => 'group-hover:text-amber-600 dark:group-hover:text-amber-400',
            ],
            'blue' => [
                'border' => 'hover:border-blue-500 dark:hover:border-blue-400',
                'bg' => 'bg-blue-100 dark:bg-blue-900/30',
                'gradient' => 'from-blue-50 to-transparent dark:from-blue-900/20',
                'text' => 'text-blue-600 dark:text-blue-400',
                'hoverText' => 'group-hover:text-blue-600 dark:group-hover:text-blue-400',
            ],
            'gray' => [
                'border' => 'hover:border-gray-500 dark:hover:border-gray-400',
                'bg' => 'bg-gray-100 dark:bg-gray-900/30',
                'gradient' => 'from-gray-50 to-transparent dark:from-gray-900/20',
                'text' => 'text-gray-600 dark:text-gray-400',
                'hoverText' => 'group-hover:text-gray-600 dark:group-hover:text-gray-400',
            ],
            'emerald' => [
                'border' => 'hover:border-emerald-500 dark:hover:border-emerald-400',
                'bg' => 'bg-emerald-100 dark:bg-emerald-900/30',
                'gradient' => 'from-emerald-50 to-transparent dark:from-emerald-900/20',
                'text' => 'text-emerald-600 dark:text-emerald-400',
                'hoverText' => 'group-hover:text-emerald-600 dark:group-hover:text-emerald-400',
            ],
            'purple' => [
                'border' => 'hover:border-purple-500 dark:hover:border-purple-400',
                'bg' => 'bg-purple-100 dark:bg-purple-900/30',
                'gradient' => 'from-purple-50 to-transparent dark:from-purple-900/20',
                'text' => 'text-purple-600 dark:text-purple-400',
                'hoverText' => 'group-hover:text-purple-600 dark:group-hover:text-purple-400',
            ],
        ];
    }

    public $importFile;

    public function updatedImportFile()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:json|max:1024', // max 1MB
        ]);

        try {
            $file = $this->importFile;
            
            // Walidacja typu pliku
            if ($file->getClientOriginalExtension() !== 'json') {
                Log::warning('Próba importu nieprawidłowego typu pliku', [
                    'extension' => $file->getClientOriginalExtension(),
                    'mime_type' => $file->getMimeType(),
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
                $this->dispatch('show-error', message: 'Nieprawidłowy typ pliku. Wymagany format: .json');
                $this->reset('importFile');
                return;
            }

            // Walidacja rozmiaru pliku
            if ($file->getSize() > 1048576) {
                $this->dispatch('show-error', message: 'Plik jest zbyt duży. Maksymalny rozmiar to 1MB.');
                $this->reset('importFile');
                return;
            }

            $content = file_get_contents($file->getRealPath());
            
            if ($content === false) {
                $this->dispatch('show-error', message: 'Nie można odczytać pliku.');
                $this->reset('importFile');
                return;
            }

            // Walidacja i import danych
            $this->importEstimate($content);
            
            // Wyczyść plik po zaimportowaniu
            $this->reset('importFile');

        } catch (\Exception $e) {
            Log::error('Błąd obsługi pliku: ' . $e->getMessage());
            $this->dispatch('show-error', message: 'Błąd podczas przetwarzania pliku: ' . $e->getMessage());
            $this->reset('importFile');
        }
    }

    private function importEstimate($fileContent)
    {
        try {
            // 1. Ograniczenie rozmiaru pliku (1MB)
            if (strlen($fileContent) > 1048576) {
                Log::warning('Próba importu zbyt dużego pliku', [
                    'size' => strlen($fileContent),
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
                $this->dispatch('show-error', message: 'Plik jest zbyt duży. Maksymalny rozmiar to 1MB.');
                return;
            }

            // 2. Dekodowanie JSON z obsługą błędów i limitem głębokości
            $data = json_decode($fileContent, true, 10); // Limit głębokości: 10
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('Próba importu nieprawidłowego pliku JSON', [
                    'error' => json_last_error_msg(),
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
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

            // 4. Walidacja wersji
            if (isset($data['version']) && version_compare($data['version'], '1.0', '<')) {
                $this->dispatch('show-error', message: 'Nieobsługiwana wersja pliku. Wymagana wersja 1.0 lub nowsza.');
                return;
            }

            // 5. Walidacja selected_services
            if (!is_array($data['selected_services'])) {
                $this->dispatch('show-error', message: 'Nieprawidłowy format danych usług.');
                return;
            }

            // 5.1. Walidacja rozmiaru tablicy (ochrona przed DoS)
            if (count($data['selected_services']) > 10000) {
                Log::warning('Próba importu pliku z zbyt dużą liczbą usług', [
                    'count' => count($data['selected_services']),
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
                $this->dispatch('show-error', message: 'Zbyt wiele usług w pliku. Maksymalnie 10,000.');
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

            if (empty($validServices)) {
                $this->dispatch('show-error', message: 'Brak prawidłowych usług w pliku.');
                return;
            }

            // 7. Walidacja i sanityzacja services_data
            $importData = [
                'selected_services' => [],
                'services_data' => [],
                'category_slug' => $data['category_slug'] ?? null,
                'selected_categories' => $data['selected_categories'] ?? [],
                'expanded_categories' => $data['expanded_categories'] ?? [],
            ];

            foreach ($validServices as $serviceId) {
                if (isset($data['selected_services'][$serviceId]) && $data['selected_services'][$serviceId]) {
                    $importData['selected_services'][$serviceId] = true;
                    
                    if (isset($data['services_data'][$serviceId]) && is_array($data['services_data'][$serviceId])) {
                        $quantity = isset($data['services_data'][$serviceId]['quantity']) 
                            ? floatval($data['services_data'][$serviceId]['quantity']) 
                            : 0;
                        
                        $price = isset($data['services_data'][$serviceId]['price']) 
                            ? floatval($data['services_data'][$serviceId]['price']) 
                            : 0;
                        
                        // Walidacja zakresu
                        if ($quantity >= 0 && $quantity <= 1000000 && $price >= 0 && $price <= 1000000) {
                            $importData['services_data'][$serviceId] = [
                                'quantity' => $quantity,
                                'price' => $price,
                            ];
                        }
                    }
                }
            }

            if (empty($importData['selected_services'])) {
                $this->dispatch('show-error', message: 'Brak prawidłowych danych do wczytania.');
                return;
            }

            // Zapisz dane do session
            session(['imported_estimate_data' => $importData]);
            
            // Przekieruj do kalkulatora (z walidacją slug)
            $categorySlug = $importData['category_slug'] ?? 'malowanie';
            
            // Walidacja slug przed przekierowaniem
            if (preg_match('/^[a-z0-9_-]+$/', $categorySlug)) {
                // Sprawdź czy kategoria istnieje
                $category = Category::where('slug', $categorySlug)
                    ->where('is_active', true)
                    ->first();
                
                if (!$category) {
                    $categorySlug = 'malowanie'; // Fallback do domyślnej kategorii
                }
            } else {
                $categorySlug = 'malowanie'; // Fallback jeśli slug nieprawidłowy
            }
            
            return $this->redirect(route('calculator.category', $categorySlug), navigate: true);

        } catch (\Exception $e) {
            Log::error('Błąd importu wyceny: ' . $e->getMessage());
            $this->dispatch('show-error', message: 'Błąd podczas wczytywania wyceny: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.landing-page', [
            'categories' => $this->categories,
            'colorClasses' => $this->colorClasses,
        ]);
    }
}
