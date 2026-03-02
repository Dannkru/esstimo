<?php

namespace App\Services\WallFinishing;

/**
 * Moduł Wykończenie Ścian – szpachlowanie/gładzie (KNR) oraz malowanie.
 */
class WallFinishingCalculatorService
{
    public const SUBSTRATE_PLASTER = 'plaster';
    public const SUBSTRATE_DRYWALL = 'drywall';
    public const FINISH_STANDARD = 'standard';
    public const FINISH_PREMIUM = 'premium';
    public const PAINT_WHITE = 'white';
    public const PAINT_COLOR = 'color';

    private const START_PLASTER_KG_M2 = 1.5;
    private const START_DRYWALL_KG_M2 = 0.0;
    private const START_DRYWALL_FULL_KG_M2 = 0.8;
    private const FINISH_STANDARD_KG_M2 = 1.0;
    private const FINISH_PREMIUM_EXTRA_KG_M2 = 0.5;
    private const PLASTER_BAG_KG = 20;
    private const PAINT_COVERAGE_M2_PER_L = 10;
    private const PRIMER_L_M2 = 0.15;
    private const PRIMER_BOTTLE_L = 5;
    private const PAINT_BUCKET_L = 10;
    private const PAINT_COLOR_EXTRA_FACTOR = 1.20;
    private const SANDPAPER_M2_PER_PIECE = 5;
    private const FOLIA_ROLL_M2 = 20;
    private const FLOOR_AREA_ESTIMATE_DIVISOR = 3;
    private const TAPE_PERIMETER_MULTIPLIER = 2;
    private const TAPE_ROLL_MB = 50;

    public function calculatePlastering(float $area_m2, string $substrate_type, string $finish_quality, array $options = []): array
    {
        $area_m2 = max(0.01, $area_m2);
        $substrate_type = strtolower($substrate_type) === self::SUBSTRATE_DRYWALL ? self::SUBSTRATE_DRYWALL : self::SUBSTRATE_PLASTER;
        $finish_quality = strtolower($finish_quality) === self::FINISH_PREMIUM ? self::FINISH_PREMIUM : self::FINISH_STANDARD;
        $full_surface_gk = !empty($options['full_surface_gk']);
        $start_kg_m2 = $substrate_type === self::SUBSTRATE_PLASTER ? self::START_PLASTER_KG_M2 : ($full_surface_gk ? self::START_DRYWALL_FULL_KG_M2 : self::START_DRYWALL_KG_M2);
        $start_total_kg = $area_m2 * $start_kg_m2;
        $glad_startowa_worki_20kg = (int) ceil($start_total_kg / self::PLASTER_BAG_KG);
        $finisz_kg_m2 = self::FINISH_STANDARD_KG_M2 + ($finish_quality === self::FINISH_PREMIUM ? self::FINISH_PREMIUM_EXTRA_KG_M2 : 0);
        $finisz_total_kg = $area_m2 * $finisz_kg_m2;
        $glad_finisz_worki_20kg = (int) ceil($finisz_total_kg / self::PLASTER_BAG_KG);
        return [
            'glad_startowa_worki_20kg' => $glad_startowa_worki_20kg,
            'glad_finisz_worki_20kg' => $glad_finisz_worki_20kg,
            'meta_plaster' => ['start_kg_m2' => $start_kg_m2, 'finisz_kg_m2' => $finisz_kg_m2, 'start_total_kg' => round($start_total_kg, 2), 'finisz_total_kg' => round($finisz_total_kg, 2)],
        ];
    }

    public function calculatePainting(float $area_m2, int $layers = 2, string $paint_type = self::PAINT_WHITE): array
    {
        $area_m2 = max(0.01, $area_m2);
        $layers = max(1, min(5, $layers));
        $paint_type = strtolower($paint_type) === self::PAINT_COLOR ? self::PAINT_COLOR : self::PAINT_WHITE;
        $grunt_total_l = $area_m2 * self::PRIMER_L_M2;
        $grunt_banki_5l = (int) ceil($grunt_total_l / self::PRIMER_BOTTLE_L);
        $paint_liters = ($area_m2 * $layers) / self::PAINT_COVERAGE_M2_PER_L;
        if ($paint_type === self::PAINT_COLOR) $paint_liters *= self::PAINT_COLOR_EXTRA_FACTOR;
        $farba_wiadra_10l = (int) ceil($paint_liters / self::PAINT_BUCKET_L);
        return [
            'grunt_banki_5l' => $grunt_banki_5l,
            'farba_wiadra_10l' => $farba_wiadra_10l,
            'meta_paint' => ['grunt_l' => round($grunt_total_l, 2), 'farba_l' => round($paint_liters, 2), 'krycie_m2_l' => self::PAINT_COVERAGE_M2_PER_L],
        ];
    }

    public function calculateConsumables(float $area_m2, ?float $floor_area_m2 = null, ?float $perimeter_m = null): array
    {
        $area_m2 = max(0.01, $area_m2);
        $papier_scierny_szt = (int) ceil($area_m2 / self::SANDPAPER_M2_PER_PIECE);
        $floor_m2 = ($floor_area_m2 !== null && $floor_area_m2 > 0) ? $floor_area_m2 : ($area_m2 / self::FLOOR_AREA_ESTIMATE_DIVISOR);
        $folia_szt = (int) ceil($floor_m2 / self::FOLIA_ROLL_M2);
        $perimeter = ($perimeter_m !== null && $perimeter_m > 0) ? $perimeter_m : (4 * sqrt($area_m2));
        $tape_mb = $perimeter * self::TAPE_PERIMETER_MULTIPLIER;
        $tasma_rolki_szt = (int) ceil($tape_mb / self::TAPE_ROLL_MB);
        return [
            'papier_scierny_szt' => $papier_scierny_szt,
            'folia_szt' => $folia_szt,
            'tasma_rolki_szt' => $tasma_rolki_szt,
            'meta_consumables' => ['floor_m2_used' => round($floor_m2, 2), 'obwod_m_used' => round($perimeter, 2)],
        ];
    }

    public function calculate(float $area_m2, string $substrate_type = self::SUBSTRATE_PLASTER, string $finish_quality = self::FINISH_STANDARD, int $layers = 2, string $paint_type = self::PAINT_WHITE, array $options = []): array
    {
        $plaster = $this->calculatePlastering($area_m2, $substrate_type, $finish_quality, $options);
        $paint = $this->calculatePainting($area_m2, $layers, $paint_type);
        $consumables = $this->calculateConsumables($area_m2, $options['floor_area_m2'] ?? null, $options['perimeter_m'] ?? null);
        return [
            'glad_startowa_worki_20kg' => $plaster['glad_startowa_worki_20kg'],
            'glad_finisz_worki_20kg' => $plaster['glad_finisz_worki_20kg'],
            'grunt_banki_5l' => $paint['grunt_banki_5l'],
            'farba_wiadra_10l' => $paint['farba_wiadra_10l'],
            'papier_scierny_szt' => $consumables['papier_scierny_szt'],
            'folia_szt' => $consumables['folia_szt'],
            'tasma_rolki_szt' => $consumables['tasma_rolki_szt'],
            'meta' => array_merge(['area_m2' => round($area_m2, 2)], $plaster['meta_plaster'] ?? [], $paint['meta_paint'] ?? [], $consumables['meta_consumables'] ?? []),
        ];
    }

    public static function resultLabels(): array
    {
        return [
            'glad_startowa_worki_20kg' => 'Gładź startowa (worki 20 kg)',
            'glad_finisz_worki_20kg' => 'Gładź finiszowa (worki 20 kg)',
            'grunt_banki_5l' => 'Grunt malarski (bańki 5 L)',
            'farba_wiadra_10l' => 'Farba (wiadra 10 L)',
            'papier_scierny_szt' => 'Papier ścierny / siatki (szt.)',
            'folia_szt' => 'Folia malarska – ochrona podłogi (szt.)',
            'tasma_rolki_szt' => 'Taśma malarska (rolki 50 mb)',
        ];
    }
}
