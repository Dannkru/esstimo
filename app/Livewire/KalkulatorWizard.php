<?php

namespace App\Livewire;

use App\Services\BathroomTech\BathroomTechCalculatorService;
use App\Services\Ceiling\CeilingCalculatorService;
use App\Services\Floor\FloorCalculatorService;
use App\Services\Insulation\InsulationCalculatorService;
use App\Services\Quote\QuoteSessionManager;
use App\Services\WallFinishing\WallFinishingCalculatorService;
use Livewire\Component;

class KalkulatorWizard extends Component
{
    /** Krok: main | drywall | ceiling_type | dimensions | result | floor_type | floor_form | result_floor | wall_finishing_form | result_wall | bathroom_form | result_bathroom | insulation_form | result_insulation | coming_soon */
    public string $step = 'main';

    /** main: sucha_zabudowa | malowanie | podlogi */
    public ?string $selectedCategory = null;

    /** drywall: sufit_podwieszany | scianka_dzialowa */
    public ?string $selectedDrywall = null;

    /** ceiling_type: krzyzowy | zwykly */
    public ?string $selectedCeilingType = null;

    /** floor_type: tiles | self_leveling | concrete */
    public ?string $selectedFloorType = null;

    public string $length = '';
    public string $width = '';
    public bool $profile4m = false;

    /** Podłogi: płytki */
    public string $floor_area = '';
    public string $tile_length_cm = '';
    public string $tile_width_cm = '';
    public string $joint_width_mm = '3';

    /** Podłogi: wylewki */
    public string $floor_thickness_mm = '';
    public string $floor_length_m = '';
    public string $floor_width_m = '';

    /** Malowanie – wykończenie ścian */
    public string $wall_area = '';
    public string $substrate_type = 'plaster';
    public string $finish_quality = 'standard';
    public bool $full_surface_gk = false;
    public string $paint_layers = '2';
    public string $paint_type = 'white';
    public string $wall_floor_area = '';
    public string $wall_perimeter = '';

    /** Łazienka – hydroizolacja */
    public string $bathroom_floor_m2 = '';
    public string $bathroom_shower_wall_m2 = '';
    public string $bathroom_corners_m = '';
    public string $bathroom_baterie = '1';

    /** Ocieplanie dachów */
    public string $insulation_roof_m2 = '';
    public string $insulation_layer1_cm = '15';
    public string $insulation_layer2_cm = '10';
    public string $insulation_rafter_cm = '80';

    public array $result = [];
    public array $resultFloor = [];
    public array $resultWall = [];
    public array $resultBathroom = [];
    public array $resultInsulation = [];

    /** Nazwa pomieszczenia przy "Dodaj do listy" */
    public string $room_name = '';

    /** Modal po dodaniu do listy */
    public bool $showAddToQuoteModal = false;

    public function selectCategory(string $category): void
    {
        $this->selectedCategory = $category;
        if ($category === 'sucha_zabudowa') {
            $this->step = 'drywall';
            $this->selectedDrywall = null;
            $this->selectedCeilingType = null;
            $this->result = [];
        } elseif ($category === 'podlogi') {
            $this->step = 'floor_type';
            $this->selectedFloorType = null;
            $this->resultFloor = [];
        } elseif ($category === 'malowanie') {
            $this->step = 'wall_finishing_form';
            $this->resultWall = [];
        } elseif ($category === 'lazienka') {
            $this->step = 'bathroom_form';
            $this->resultBathroom = [];
        } elseif ($category === 'ocieplenie') {
            $this->step = 'insulation_form';
            $this->resultInsulation = [];
        } else {
            $this->step = 'coming_soon';
        }
    }

    public function selectDrywall(string $option): void
    {
        $this->selectedDrywall = $option;
        if ($option === 'sufit_podwieszany') {
            $this->step = 'ceiling_type';
            $this->selectedCeilingType = null;
            $this->result = [];
        } else {
            $this->step = 'coming_soon';
        }
    }

    public function selectCeilingType(string $type): void
    {
        $this->selectedCeilingType = $type;
        $this->step = 'dimensions';
        $this->result = [];
    }

    public function calculate(): void
    {
        $this->validate([
            'length' => ['required', 'numeric', 'min:0.1', 'max:100'],
            'width' => ['required', 'numeric', 'min:0.1', 'max:100'],
        ], [
            'length.required' => 'Podaj długość pomieszczenia (m).',
            'width.required' => 'Podaj szerokość pomieszczenia (m).',
        ]);

        $calculator = app(CeilingCalculatorService::class);
        $this->result = $calculator->calculateSuspendedCeiling(
            $this->selectedCeilingType,
            (float) $this->length,
            (float) $this->width,
            ['profile_length' => $this->profile4m ? 4.0 : 3.0]
        );
        $this->step = 'result';
    }

    public function backToMain(): void
    {
        $this->step = 'main';
        $this->selectedCategory = null;
        $this->selectedDrywall = null;
        $this->selectedCeilingType = null;
        $this->selectedFloorType = null;
        $this->length = '';
        $this->width = '';
        $this->result = [];
        $this->resultFloor = [];
        $this->resultWall = [];
        $this->resultBathroom = [];
        $this->resultInsulation = [];
    }

    public function backToDrywall(): void
    {
        $this->step = 'drywall';
        $this->selectedDrywall = null;
        $this->selectedCeilingType = null;
        $this->result = [];
    }

    public function backToCeilingType(): void
    {
        $this->step = 'ceiling_type';
        $this->selectedCeilingType = null;
        $this->result = [];
    }

    public function backToDimensions(): void
    {
        $this->step = 'dimensions';
        $this->result = [];
    }

    public function selectFloorType(string $type): void
    {
        $this->selectedFloorType = $type;
        $this->step = 'floor_form';
        $this->resultFloor = [];
    }

    public function calculateFloor(): void
    {
        $floor = app(FloorCalculatorService::class);
        if ($this->selectedFloorType === 'tiles') {
            $this->validate([
                'floor_area' => ['required', 'numeric', 'min:0.1', 'max:1000'],
                'tile_length_cm' => ['required', 'numeric', 'min:1', 'max:300'],
                'tile_width_cm' => ['required', 'numeric', 'min:1', 'max:300'],
                'joint_width_mm' => ['required', 'numeric', 'min:0', 'max:20'],
            ], [
                'floor_area.required' => 'Podaj powierzchnię (m²).',
                'tile_length_cm.required' => 'Podaj długość płytki (cm).',
                'tile_width_cm.required' => 'Podaj szerokość płytki (cm).',
            ]);
            $this->resultFloor = $floor->calculateTiles(
                (float) $this->floor_area,
                (float) $this->tile_length_cm,
                (float) $this->tile_width_cm,
                (float) $this->joint_width_mm
            );
        } elseif ($this->selectedFloorType === 'self_leveling') {
            $this->validate([
                'floor_area' => ['required', 'numeric', 'min:0.1', 'max:1000'],
                'floor_thickness_mm' => ['required', 'numeric', 'min:1', 'max:50'],
            ], [
                'floor_area.required' => 'Podaj powierzchnię (m²).',
                'floor_thickness_mm.required' => 'Podaj grubość warstwy (mm).',
            ]);
            $this->resultFloor = $floor->calculateSelfLeveling(
                (float) $this->floor_area,
                (float) $this->floor_thickness_mm
            );
        } else {
            $this->validate([
                'floor_area' => ['required', 'numeric', 'min:0.1', 'max:1000'],
                'floor_thickness_mm' => ['required', 'numeric', 'min:10', 'max:150'],
            ], [
                'floor_area.required' => 'Podaj powierzchnię (m²).',
                'floor_thickness_mm.required' => 'Podaj grubość jastrychu (mm).',
            ]);
            $length_m = $this->floor_length_m !== '' ? (float) $this->floor_length_m : null;
            $width_m = $this->floor_width_m !== '' ? (float) $this->floor_width_m : null;
            $this->resultFloor = $floor->calculateConcreteScreed(
                (float) $this->floor_area,
                (float) $this->floor_thickness_mm,
                $length_m,
                $width_m
            );
        }
        $this->step = 'result_floor';
    }

    public function backToFloorType(): void
    {
        $this->step = 'floor_type';
        $this->selectedFloorType = null;
        $this->resultFloor = [];
    }

    public function backToFloorForm(): void
    {
        $this->step = 'floor_form';
        $this->resultFloor = [];
    }

    public function calculateWall(): void
    {
        $this->validate([
            'wall_area' => ['required', 'numeric', 'min:0.1', 'max:1000'],
            'paint_layers' => ['required', 'integer', 'min:1', 'max:5'],
        ], [
            'wall_area.required' => 'Podaj powierzchnię ścian (m²).',
        ]);

        $opts = ['full_surface_gk' => $this->full_surface_gk];
        if ($this->wall_floor_area !== '') {
            $opts['floor_area_m2'] = (float) $this->wall_floor_area;
        }
        if ($this->wall_perimeter !== '') {
            $opts['perimeter_m'] = (float) $this->wall_perimeter;
        }

        $wall = app(WallFinishingCalculatorService::class);
        $this->resultWall = $wall->calculate(
            (float) $this->wall_area,
            $this->substrate_type,
            $this->finish_quality,
            (int) $this->paint_layers,
            $this->paint_type,
            $opts
        );
        $this->step = 'result_wall';
    }

    public function backToWallForm(): void
    {
        $this->step = 'wall_finishing_form';
        $this->resultWall = [];
    }

    public function calculateBathroom(): void
    {
        $this->validate([
            'bathroom_floor_m2' => ['required', 'numeric', 'min:0.1', 'max:500'],
            'bathroom_shower_wall_m2' => ['required', 'numeric', 'min:0', 'max:100'],
            'bathroom_baterie' => ['required', 'integer', 'min:0', 'max:20'],
        ], [
            'bathroom_floor_m2.required' => 'Podaj powierzchnię podłogi (m²).',
            'bathroom_shower_wall_m2.required' => 'Podaj strefę mokrą ścian (m²).',
        ]);

        $corners = $this->bathroom_corners_m !== '' && (float) $this->bathroom_corners_m > 0
            ? (float) $this->bathroom_corners_m
            : null;

        $bathroom = app(BathroomTechCalculatorService::class);
        $this->resultBathroom = $bathroom->calculateWaterproofing(
            (float) $this->bathroom_floor_m2,
            (float) $this->bathroom_shower_wall_m2,
            $corners,
            (int) $this->bathroom_baterie
        );
        $this->step = 'result_bathroom';
    }

    public function backToBathroomForm(): void
    {
        $this->step = 'bathroom_form';
        $this->resultBathroom = [];
    }

    public function calculateInsulation(): void
    {
        $this->validate([
            'insulation_roof_m2' => ['required', 'numeric', 'min:0.1', 'max:1000'],
            'insulation_layer1_cm' => ['required', 'numeric', 'min:0', 'max:30'],
            'insulation_layer2_cm' => ['required', 'numeric', 'min:0', 'max:30'],
            'insulation_rafter_cm' => ['required', 'numeric', 'min:40', 'max:120'],
        ], [
            'insulation_roof_m2.required' => 'Podaj powierzchnię skosów (m²).',
        ]);

        $insulation = app(InsulationCalculatorService::class);
        $this->resultInsulation = $insulation->calculateAtticInsulation(
            (float) $this->insulation_roof_m2,
            (float) $this->insulation_layer1_cm,
            (float) $this->insulation_layer2_cm,
            (float) $this->insulation_rafter_cm
        );
        $this->step = 'result_insulation';
    }

    public function backToInsulationForm(): void
    {
        $this->step = 'insulation_form';
        $this->resultInsulation = [];
    }

    public function addToQuote(): void
    {
        $this->validate([
            'room_name' => ['required', 'string', 'max:200'],
        ], [
            'room_name.required' => 'Podaj nazwę pomieszczenia lub etapu.',
        ]);

        $manager = app(QuoteSessionManager::class);
        $manager->addItem([
            'category' => $this->getCurrentQuoteCategory(),
            'category_key' => $this->getCurrentCategoryKey(),
            'room_name' => trim($this->room_name),
            'parameters' => $this->getCurrentParameters(),
            'materials' => $this->getCurrentMaterials(),
        ]);
        $this->showAddToQuoteModal = true;
    }

    public function goToSummary()
    {
        $this->showAddToQuoteModal = false;
        return $this->redirect(route('materials.summary'));
    }

    public function closeAddToQuoteModalAndAddAnother(): void
    {
        $this->showAddToQuoteModal = false;
        $this->backToMain();
    }

    private function getCurrentCategoryKey(): string
    {
        return match ($this->step) {
            'result' => 'ceiling',
            'result_floor' => 'floor',
            'result_wall' => 'wall',
            'result_bathroom' => 'bathroom',
            'result_insulation' => 'insulation',
            default => 'other',
        };
    }

    private function getCurrentQuoteCategory(): string
    {
        return match ($this->step) {
            'result' => 'Sufit Podwieszany',
            'result_floor' => match ($this->selectedFloorType) {
                'tiles' => 'Podłoga – Płytki',
                'self_leveling' => 'Podłoga – Wylewka samopoziomująca',
                'concrete' => 'Podłoga – Jastrych',
                default => 'Podłoga',
            },
            'result_wall' => 'Malowanie – szpachlowanie',
            'result_bathroom' => 'Łazienka',
            'result_insulation' => 'Ocieplanie dachów',
            default => 'Kalkulacja',
        };
    }

    private function getCurrentParameters(): array
    {
        return match ($this->step) {
            'result' => [
                'length' => $this->length,
                'width' => $this->width,
                'type' => $this->selectedCeilingType,
                'profile_4m' => $this->profile4m,
            ],
            'result_floor' => [
                'area' => $this->floor_area,
                'type' => $this->selectedFloorType,
                'tile_length_cm' => $this->tile_length_cm,
                'tile_width_cm' => $this->tile_width_cm,
                'thickness_mm' => $this->floor_thickness_mm,
            ],
            'result_wall' => [
                'area' => $this->wall_area,
                'substrate_type' => $this->substrate_type,
                'paint_layers' => $this->paint_layers,
            ],
            'result_bathroom' => [
                'floor_m2' => $this->bathroom_floor_m2,
                'shower_wall_m2' => $this->bathroom_shower_wall_m2,
                'baterie' => $this->bathroom_baterie,
            ],
            'result_insulation' => [
                'roof_m2' => $this->insulation_roof_m2,
                'layer1_cm' => $this->insulation_layer1_cm,
                'layer2_cm' => $this->insulation_layer2_cm,
                'rafter_cm' => $this->insulation_rafter_cm,
            ],
            default => [],
        };
    }

    private function getCurrentMaterials(): array
    {
        $materials = match ($this->step) {
            'result' => $this->result,
            'result_floor' => $this->resultFloor,
            'result_wall' => $this->resultWall,
            'result_bathroom' => $this->resultBathroom['hydroizolacja'] ?? [],
            'result_insulation' => $this->resultInsulation['ocieplenie'] ?? [],
            default => [],
        };
        if (! is_array($materials)) {
            return [];
        }
        return array_filter($materials, fn ($key) => $key !== 'meta', ARRAY_FILTER_USE_KEY);
    }

    public function render()
    {
        return view('livewire.kalkulator-wizard', [
            'labels' => CeilingCalculatorService::materialLabels(),
            'floorLabels' => FloorCalculatorService::floorResultLabels(),
            'wallLabels' => WallFinishingCalculatorService::resultLabels(),
            'hydroizolacjaLabels' => BathroomTechCalculatorService::hydroizolacjaLabels(),
            'ocieplenieLabels' => InsulationCalculatorService::ocieplenieLabels(),
        ]);
    }
}
