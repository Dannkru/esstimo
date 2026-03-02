<?php

namespace App\Services\BathroomTech;

/**
 * Moduł Hydroizolacja łazienki (BathroomTech).
 */
class BathroomTechCalculatorService
{
    private const LIQUID_FOIL_KG_M2 = 1.2;
    private const BUCKET_5_KG = 5;
    private const BUCKET_15_KG = 15;
    private const TAPE_WASTE_FACTOR = 1.10;
    private const TAPE_ROLL_MB = 10;
    private const TAPE_ROLL_50_MB = 50;
    private const SILICONE_MB_PER_TUBE = 11;
    private const CUFFS_PER_TAP = 2;
    private const CORNERS_ESTIMATE_SQRT_FACTOR = 4.0;
    private const CORNERS_ESTIMATE_SHOWER_ADD_M = 5.0;

    public function calculateWaterproofing(float $floor_area_m2, float $shower_wall_area_m2, ?float $corners_perimeter_m = null, int $baterie = 1): array
    {
        $floor_area_m2 = max(0.01, $floor_area_m2);
        $shower_wall_area_m2 = max(0, $shower_wall_area_m2);
        $baterie = max(0, $baterie);
        $corners_m = ($corners_perimeter_m !== null && $corners_perimeter_m > 0) ? $corners_perimeter_m : $this->estimateCornersPerimeter($floor_area_m2);
        $liquid_area_m2 = $floor_area_m2 + $shower_wall_area_m2;
        $folia_plynna_kg = round($liquid_area_m2 * self::LIQUID_FOIL_KG_M2, 2);
        [$folia_5kg_szt, $folia_15kg_szt] = $this->optimalFoliaPackages($folia_plynna_kg);
        $tasma_mb = round($corners_m * self::TAPE_WASTE_FACTOR, 2);
        $tasma_rolki_10_szt = (int) ceil($tasma_mb / self::TAPE_ROLL_MB);
        $tasma_rolki_50_szt = (int) ceil($tasma_mb / self::TAPE_ROLL_50_MB);
        $mankety_szt = $baterie * self::CUFFS_PER_TAP;
        $silikon_szt = (int) ceil($tasma_mb / self::SILICONE_MB_PER_TUBE);
        return [
            'hydroizolacja' => [
                'folia_plynna_kg' => $folia_plynna_kg,
                'folia_5kg_szt' => $folia_5kg_szt,
                'folia_15kg_szt' => $folia_15kg_szt,
                'tasma_mb' => $tasma_mb,
                'tasma_rolki_10mb_szt' => $tasma_rolki_10_szt,
                'tasma_rolki_50mb_szt' => $tasma_rolki_50_szt,
                'mankety_szt' => $mankety_szt,
                'silikon_szt' => $silikon_szt,
            ],
            'meta' => [
                'floor_area_m2' => round($floor_area_m2, 2),
                'shower_wall_area_m2' => round($shower_wall_area_m2, 2),
                'corners_perimeter_m' => round($corners_m, 2),
                'liquid_area_m2' => round($liquid_area_m2, 2),
            ],
        ];
    }

    private function estimateCornersPerimeter(float $floor_area_m2): float
    {
        return self::CORNERS_ESTIMATE_SQRT_FACTOR * sqrt($floor_area_m2) + self::CORNERS_ESTIMATE_SHOWER_ADD_M;
    }

    private function optimalFoliaPackages(float $total_kg): array
    {
        $n15 = (int) floor($total_kg / self::BUCKET_15_KG);
        $remainder = $total_kg - $n15 * self::BUCKET_15_KG;
        $n5 = $remainder <= 0 ? 0 : (int) ceil($remainder / self::BUCKET_5_KG);
        return [$n5, $n15];
    }

    public static function hydroizolacjaLabels(): array
    {
        return [
            'folia_plynna_kg' => 'Folia w płynie (kg)',
            'folia_5kg_szt' => 'Folia w płynie – wiaderka 5 kg (szt.)',
            'folia_15kg_szt' => 'Folia w płynie – wiaderka 15 kg (szt.)',
            'tasma_mb' => 'Taśma uszczelniająca (mb)',
            'tasma_rolki_10mb_szt' => 'Taśma uszczelniająca – rolki 10 mb (szt.)',
            'tasma_rolki_50mb_szt' => 'Taśma uszczelniająca – rolki 50 mb (szt.)',
            'mankety_szt' => 'Mankiety uszczelniające (szt.)',
            'silikon_szt' => 'Silikon sanitarny – tuby 280 ml (szt.)',
        ];
    }
}
