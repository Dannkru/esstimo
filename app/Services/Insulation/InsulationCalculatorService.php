<?php

namespace App\Services\Insulation;

/**
 * Moduł Ocieplenie poddasza (Insulation).
 */
class InsulationCalculatorService
{
    private const WOOL_LAYER1_WASTE = 1.15;
    private const WOOL_LAYER2_WASTE = 1.05;
    private const VAPOR_OVERLAP_FACTOR = 1.15;
    private const VAPOR_ROLL_M2 = 100;
    private const TAPE_MB_PER_M2_FOLIA = 1.0;
    private const TAPE_ROLL_25_MB = 25;
    private const TAPE_ROLL_50_MB = 50;
    private const HANGER_SPACING_M = 0.60;
    private const PROFILE_SPACING_M = 0.40;
    private const PROFILE_LENGTH_M = 3.0;
    private const WOOL_ROLL_APPROX_M2 = 6.0;

    public function calculateAtticInsulation(float $roof_area_m2, float $layer1_thickness_cm = 15.0, float $layer2_thickness_cm = 10.0, float $rafter_spacing_cm = 80.0): array
    {
        $roof_area_m2 = max(0.01, $roof_area_m2);
        $layer1_thickness_cm = max(0, $layer1_thickness_cm);
        $layer2_thickness_cm = max(0, $layer2_thickness_cm);
        $rafter_spacing_cm = max(1, $rafter_spacing_cm);
        $rafter_spacing_m = $rafter_spacing_cm / 100;
        $welna_warstwa1_m2 = round($roof_area_m2 * self::WOOL_LAYER1_WASTE, 2);
        $welna_warstwa2_m2 = round($roof_area_m2 * self::WOOL_LAYER2_WASTE, 2);
        $folia_paro_m2 = round($roof_area_m2 * self::VAPOR_OVERLAP_FACTOR, 2);
        $folia_paro_rolki_100_szt = (int) ceil($folia_paro_m2 / self::VAPOR_ROLL_M2);
        $tasma_do_folii_mb = round($roof_area_m2 * self::TAPE_MB_PER_M2_FOLIA * self::VAPOR_OVERLAP_FACTOR, 2);
        $tasma_do_folii_rolki_25_szt = (int) ceil($tasma_do_folii_mb / self::TAPE_ROLL_25_MB);
        $tasma_do_folii_rolki_50_szt = (int) ceil($tasma_do_folii_mb / self::TAPE_ROLL_50_MB);
        $rafter_total_m = $roof_area_m2 / $rafter_spacing_m;
        $grzybki_szt = (int) ceil($rafter_total_m / self::HANGER_SPACING_M);
        $profile_cd60_m = $roof_area_m2 / self::PROFILE_SPACING_M;
        $profile_cd60_szt = (int) ceil($profile_cd60_m / self::PROFILE_LENGTH_M);
        $rolki_warstwa1_approx = (int) ceil($welna_warstwa1_m2 / self::WOOL_ROLL_APPROX_M2);
        $rolki_warstwa2_approx = (int) ceil($welna_warstwa2_m2 / self::WOOL_ROLL_APPROX_M2);
        return [
            'ocieplenie' => [
                'welna_warstwa1_m2' => $welna_warstwa1_m2,
                'welna_warstwa2_m2' => $welna_warstwa2_m2,
                'welna_warstwa1_rolki_approx_szt' => $rolki_warstwa1_approx,
                'welna_warstwa2_rolki_approx_szt' => $rolki_warstwa2_approx,
                'folia_paro_m2' => $folia_paro_m2,
                'folia_paro_rolki_100_szt' => $folia_paro_rolki_100_szt,
                'tasma_do_folii_mb' => $tasma_do_folii_mb,
                'tasma_do_folii_rolki_25_szt' => $tasma_do_folii_rolki_25_szt,
                'tasma_do_folii_rolki_50_szt' => $tasma_do_folii_rolki_50_szt,
                'grzybki_szt' => $grzybki_szt,
                'profile_cd60_szt' => $profile_cd60_szt,
            ],
            'meta' => [
                'roof_area_m2' => round($roof_area_m2, 2),
                'layer1_thickness_cm' => round($layer1_thickness_cm, 1),
                'layer2_thickness_cm' => round($layer2_thickness_cm, 1),
                'rafter_spacing_cm' => round($rafter_spacing_cm, 1),
                'rafter_total_m' => round($rafter_total_m, 2),
                'wool_roll_approx_m2' => self::WOOL_ROLL_APPROX_M2,
            ],
        ];
    }

    public static function ocieplenieLabels(): array
    {
        return [
            'welna_warstwa1_m2' => 'Wełna mineralna – warstwa 1 (m²)',
            'welna_warstwa2_m2' => 'Wełna mineralna – warstwa 2 (m²)',
            'welna_warstwa1_rolki_approx_szt' => 'Wełna warstwa 1 – ok. rolek 15 cm (szt.)',
            'welna_warstwa2_rolki_approx_szt' => 'Wełna warstwa 2 – ok. rolek 10 cm (szt.)',
            'folia_paro_m2' => 'Folia paroizolacyjna (m²)',
            'folia_paro_rolki_100_szt' => 'Folia paro – rolki 100 m² (szt.)',
            'tasma_do_folii_mb' => 'Taśma do sklejania folii (mb)',
            'tasma_do_folii_rolki_25_szt' => 'Taśma – rolki 25 mb (szt.)',
            'tasma_do_folii_rolki_50_szt' => 'Taśma – rolki 50 mb (szt.)',
            'grzybki_szt' => 'Wieszaki (grzybki/ES) (szt.)',
            'profile_cd60_szt' => 'Profile CD60 – zabudowa skosów (szt.)',
        ];
    }
}
