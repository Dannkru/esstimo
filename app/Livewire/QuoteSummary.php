<?php

namespace App\Livewire;

use App\Models\Material;
use App\Services\BathroomTech\BathroomTechCalculatorService;
use App\Services\Ceiling\CeilingCalculatorService;
use App\Services\Floor\FloorCalculatorService;
use App\Services\Insulation\InsulationCalculatorService;
use App\Services\Quote\QuoteSessionManager;
use App\Services\WallFinishing\WallFinishingCalculatorService;
use Livewire\Component;

class QuoteSummary extends Component
{
    /** Row IDs for expanded "Check Prices" panels (multiple can be open). */
    public array $expandedRows = [];

    public function toggleRow(string $id): void
    {
        if (in_array($id, $this->expandedRows, true)) {
            $this->expandedRows = array_values(array_filter(
                $this->expandedRows,
                fn (string $rowId): bool => $rowId !== $id
            ));
        } else {
            $this->expandedRows[] = $id;
        }
    }

    public function removeItem(string $id): void
    {
        app(QuoteSessionManager::class)->removeItem($id);
    }

    public function getItems(): array
    {
        return app(QuoteSessionManager::class)->getItems();
    }

    public function getAggregatedMaterials(): array
    {
        return app(QuoteSessionManager::class)->aggregateMaterials();
    }

    public static function labelsForCategory(string $categoryKey): array
    {
        return match ($categoryKey) {
            'ceiling' => CeilingCalculatorService::materialLabels(),
            'floor' => FloorCalculatorService::floorResultLabels(),
            'wall' => WallFinishingCalculatorService::resultLabels(),
            'bathroom' => BathroomTechCalculatorService::hydroizolacjaLabels(),
            'insulation' => InsulationCalculatorService::ocieplenieLabels(),
            default => [],
        };
    }

    public static function mergedMaterialLabels(): array
    {
        return array_merge(
            CeilingCalculatorService::materialLabels(),
            FloorCalculatorService::floorResultLabels(),
            WallFinishingCalculatorService::resultLabels(),
            BathroomTechCalculatorService::hydroizolacjaLabels(),
            InsulationCalculatorService::ocieplenieLabels()
        );
    }

    public function formatMaterialValue(string $key, int|float $value): string
    {
        return self::formatValue($key, $value);
    }

    public static function formatValue(string $key, int|float $value): string
    {
        if (str_ends_with($key, '_l')) {
            return number_format((float) $value, 2, ',', ' ') . ' L';
        }
        if (str_ends_with($key, '_kg') || str_ends_with($key, '_mb')) {
            return number_format((float) $value, 2, ',', ' ') . (str_ends_with($key, '_kg') ? ' kg' : ' mb');
        }
        if (str_ends_with($key, '_m2')) {
            return number_format((float) $value, 2, ',', ' ') . ' m²';
        }
        return (string) (is_float($value) ? number_format($value, 2, ',', ' ') : $value) . ' szt.';
    }

    public function render()
    {
        $items = $this->getItems();
        $aggregated = $this->getAggregatedMaterials();
        $mergedLabels = self::mergedMaterialLabels();
        $searchTerms = $this->getSearchTermsMap();

        return view('livewire.quote-summary', [
            'items' => $items,
            'aggregated' => $aggregated,
            'mergedLabels' => $mergedLabels,
            'searchTerms' => $searchTerms,
        ]);
    }

    /**
     * Map material_key => search query (search_term ?? name from DB, or fallback to label).
     */
    private function getSearchTermsMap(): array
    {
        try {
            $map = Material::searchTermsMap();
            if ($map !== []) {
                return $map;
            }
        } catch (\Throwable) {
            // Table may not exist yet
        }

        return [];
    }
}
