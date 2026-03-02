<?php

namespace App\Services\Floor;

/**
 * Moduł Podłogi – kalkulacja materiałów: płytki, wylewka samopoziomująca, wylewka betonowa.
 * Wzory dokładne (bez uproszczeń), aby uniknąć braku materiału na budowie.
 */
class FloorCalculatorService
{
    public const SCENARIO_TILES = 'tiles';
    public const SCENARIO_SELF_LEVELING = 'self_leveling';
    public const SCENARIO_CONCRETE = 'concrete';

    private const ADHESIVE_SMALL_MAX_CM = 30;
    private const ADHESIVE_MEDIUM_MAX_CM = 60;
    private const ADHESIVE_SMALL_KG_M2 = 3.0;
    private const ADHESIVE_MEDIUM_KG_M2 = 4.5;
    private const ADHESIVE_LARGE_KG_M2 = 6.5;
    private const ADHESIVE_WASTE_FACTOR = 1.05;
    private const ADHESIVE_BAG_KG = 25;
    private const GROUT_DENSITY_KG_M3 = 1600;
    private const GROUT_DEPTH_STANDARD_MM = 5;
    private const GROUT_DEPTH_THICK_MM = 8;
    private const PRIMER_KG_M2 = 0.2;
    private const SELF_LEVELING_KG_PER_M2_PER_MM = 1.7;
    private const SELF_LEVELING_BAG_KG = 25;
    private const SELF_LEVELING_THIN_MM_THRESHOLD = 5;
    private const SELF_LEVELING_THIN_WASTE_FACTOR = 1.10;
    private const CONCRETE_KG_PER_M2_PER_MM = 2.0;
    private const CONCRETE_BAG_KG = 25;
    private const LEVELING_CLIPS_PER_M2 = 11;
    private const LEVELING_MIN_SIDE_CM = 50;

    public function calculateTiles(
        float $area_m2,
        float $tile_length_cm,
        float $tile_width_cm,
        float $joint_width_mm,
        array $options = []
    ): array {
        $area_m2 = max(0.01, $area_m2);
        $tile_length_cm = max(0.1, $tile_length_cm);
        $tile_width_cm = max(0.1, $tile_width_cm);
        $joint_width_mm = max(0, $joint_width_mm);
        $longer_side_cm = max($tile_length_cm, $tile_width_cm);
        $klej_kg_m2 = $this->getAdhesiveConsumptionKgM2($longer_side_cm);
        $total_klej_kg = $area_m2 * $klej_kg_m2 * self::ADHESIVE_WASTE_FACTOR;
        $klej_worki_25kg = (int) ceil($total_klej_kg / self::ADHESIVE_BAG_KG);
        $depth_mm = isset($options['tile_thickness_cm']) && (float) $options['tile_thickness_cm'] > 1.0 ? self::GROUT_DEPTH_THICK_MM : self::GROUT_DEPTH_STANDARD_MM;
        $fuga_kg_m2 = $this->groutConsumptionKgM2($tile_length_cm, $tile_width_cm, $joint_width_mm, $depth_mm);
        $fuga_kg = round($area_m2 * $fuga_kg_m2, 2);
        $grunt_l = $this->primerLiters($area_m2);
        $klipsy_szt = $longer_side_cm >= self::LEVELING_MIN_SIDE_CM ? (int) ceil($area_m2 * self::LEVELING_CLIPS_PER_M2) : 0;
        return [
            'klej_worki_25kg' => $klej_worki_25kg,
            'fuga_kg' => $fuga_kg,
            'wylewka_worki_25kg' => 0,
            'grunt_l' => $grunt_l,
            'tasma_dylatacyjna_mb' => 0.0,
            'system_poziomowania_klipsy_szt' => $klipsy_szt,
            'meta' => ['scenario' => self::SCENARIO_TILES, 'area_m2' => round($area_m2, 2), 'klej_kg_m2' => round($klej_kg_m2, 2), 'fuga_kg_m2' => round($fuga_kg_m2, 4), 'grzebien_klasa' => $this->adhesiveClassLabel($longer_side_cm)],
        ];
    }

    public function calculateSelfLeveling(float $area_m2, float $thickness_mm): array
    {
        $area_m2 = max(0.01, $area_m2);
        $thickness_mm = max(0.1, $thickness_mm);
        $total_kg = $area_m2 * $thickness_mm * self::SELF_LEVELING_KG_PER_M2_PER_MM;
        if ($thickness_mm < self::SELF_LEVELING_THIN_MM_THRESHOLD) $total_kg *= self::SELF_LEVELING_THIN_WASTE_FACTOR;
        $wylewka_worki_25kg = (int) ceil($total_kg / self::SELF_LEVELING_BAG_KG);
        $grunt_l = $this->primerLiters($area_m2);
        return [
            'klej_worki_25kg' => 0,
            'fuga_kg' => 0.0,
            'wylewka_worki_25kg' => $wylewka_worki_25kg,
            'grunt_l' => $grunt_l,
            'tasma_dylatacyjna_mb' => 0.0,
            'system_poziomowania_klipsy_szt' => 0,
            'meta' => ['scenario' => self::SCENARIO_SELF_LEVELING, 'area_m2' => round($area_m2, 2), 'thickness_mm' => round($thickness_mm, 1), 'total_kg' => round($total_kg, 2), 'dodatkowy_zapas_10' => $thickness_mm < self::SELF_LEVELING_THIN_MM_THRESHOLD],
        ];
    }

    public function calculateConcreteScreed(float $area_m2, float $thickness_mm, ?float $length_m = null, ?float $width_m = null): array
    {
        $area_m2 = max(0.01, $area_m2);
        $thickness_mm = max(0.1, $thickness_mm);
        $total_kg = $area_m2 * $thickness_mm * self::CONCRETE_KG_PER_M2_PER_MM;
        $wylewka_worki_25kg = (int) ceil($total_kg / self::CONCRETE_BAG_KG);
        $grunt_l = $this->primerLiters($area_m2);
        $tasma_dylatacyjna_mb = ($length_m !== null && $width_m !== null && $length_m > 0 && $width_m > 0) ? (float)(($length_m + $width_m) * 2) : (float)(4 * sqrt($area_m2));
        $tasma_dylatacyjna_mb = round($tasma_dylatacyjna_mb, 2);
        return [
            'klej_worki_25kg' => 0,
            'fuga_kg' => 0.0,
            'wylewka_worki_25kg' => $wylewka_worki_25kg,
            'grunt_l' => $grunt_l,
            'tasma_dylatacyjna_mb' => $tasma_dylatacyjna_mb,
            'system_poziomowania_klipsy_szt' => 0,
            'meta' => ['scenario' => self::SCENARIO_CONCRETE, 'area_m2' => round($area_m2, 2), 'thickness_mm' => round($thickness_mm, 1), 'total_kg' => round($total_kg, 2), 'obwod_m' => $tasma_dylatacyjna_mb],
        ];
    }

    private function getAdhesiveConsumptionKgM2(float $longer_side_cm): float
    {
        if ($longer_side_cm < self::ADHESIVE_SMALL_MAX_CM) return self::ADHESIVE_SMALL_KG_M2;
        if ($longer_side_cm <= self::ADHESIVE_MEDIUM_MAX_CM) return self::ADHESIVE_MEDIUM_KG_M2;
        return self::ADHESIVE_LARGE_KG_M2;
    }

    private function adhesiveClassLabel(float $longer_side_cm): string
    {
        if ($longer_side_cm < self::ADHESIVE_SMALL_MAX_CM) return 'mała (<30 cm), grzebień 6–8 mm';
        if ($longer_side_cm <= self::ADHESIVE_MEDIUM_MAX_CM) return 'średnia (30–60 cm), grzebień 10 mm';
        return 'duża (>60 cm), grzebień 12+ mm, metoda kombinowana';
    }

    private function groutConsumptionKgM2(float $tile_length_cm, float $tile_width_cm, float $joint_width_mm, float $depth_mm): float
    {
        $l = $tile_length_cm / 100;
        $w = $tile_width_cm / 100;
        $joint_length_per_m2 = (1 / $l + 1 / $w);
        $joint_width_m = $joint_width_mm / 1000;
        $depth_m = $depth_mm / 1000;
        $volume_m3_per_m2 = $joint_length_per_m2 * $joint_width_m * $depth_m;
        return (float)($volume_m3_per_m2 * self::GROUT_DENSITY_KG_M3);
    }

    private function primerLiters(float $area_m2): float
    {
        return round($area_m2 * self::PRIMER_KG_M2, 2);
    }

    public static function floorResultLabels(): array
    {
        return [
            'klej_worki_25kg' => 'Klej do płytek (worki 25 kg)',
            'fuga_kg' => 'Fuga (kg)',
            'wylewka_worki_25kg' => 'Wylewka (worki 25 kg)',
            'grunt_l' => 'Grunt (L)',
            'tasma_dylatacyjna_mb' => 'Taśma dylatacyjna obwodowa (mb)',
            'system_poziomowania_klipsy_szt' => 'System poziomowania – klipsy (szt.)',
        ];
    }
}
