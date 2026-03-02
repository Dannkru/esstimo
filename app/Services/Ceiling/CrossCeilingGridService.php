<?php

namespace App\Services\Ceiling;

/**
 * Serwis obliczający materiały na sufit podwieszany krzyżowy (Grid Logic).
 * Uwzględnia geometrię pomieszczenia (pokój 5×5 m vs korytarz 25×1 m).
 */
class CrossCeilingGridService
{
    public const PROFILE_LENGTH_3M = 3.0;
    public const PROFILE_LENGTH_4M = 4.0;
    public const PANEL_AREA_M2 = 2.4;  // 1.2 m × 2.0 m
    public const MAIN_SPACING_M = 1.0;
    public const CARRYING_SPACING_M = 0.4;
    public const HANGER_SPACING_M = 0.8;
    public const CD60_WASTE_FACTOR = 1.10;
    public const SCREWS_PER_M2_DEFAULT = 22;
    public const PCHELKI_PER_HANGER = 2;
    public const PCHELKI_PER_LONGITUDINAL = 4;

    /**
     * Oblicza materiały na sufit krzyżowy.
     *
     * @return array{profile_ud: int, profile_cd: int, plyty: int, wieszaki: int, laczniki: int, wkrety: int, wkrety_pchelki: int, meta: array}
     */
    public function calculate(
        float $length,
        float $width,
        ?float $wasteFactor = null,
        float $profileLength = self::PROFILE_LENGTH_3M
    ): array {
        $length = max(0.1, $length);
        $width = max(0.1, $width);
        $profileLength = $profileLength === self::PROFILE_LENGTH_4M ? self::PROFILE_LENGTH_4M : self::PROFILE_LENGTH_3M;

        $longerSide = max($length, $width);
        $shorterSide = min($length, $width);
        $area = $length * $width;

        if ($wasteFactor === null) {
            $ratio = $longerSide / max(0.01, $shorterSide);
            $wasteFactor = $ratio > 4 ? 0.20 : 0.10;
        }
        $wasteFactor = max(0, min(1, $wasteFactor));

        $profileUd = $this->calculateProfileUd($length, $width, $profileLength);
        $plyty = $this->calculatePlyty($area, $wasteFactor);
        $cd60 = $this->calculateCd60($longerSide, $shorterSide, $profileLength);
        $wieszaki = $this->calculateWieszaki($cd60['main_length_m']);
        $laczniki = $cd60['main_rows'] * $cd60['carrying_rows'];
        $wkrety = (int) ceil($area * self::SCREWS_PER_M2_DEFAULT);
        $wkretyPchelki = $this->calculatePchelki($wieszaki, $cd60, $profileLength);

        return [
            'profile_ud' => $profileUd,
            'profile_cd' => $cd60['pieces'],
            'plyty' => $plyty,
            'wieszaki' => $wieszaki,
            'laczniki' => $laczniki,
            'wkrety' => $wkrety,
            'wkrety_pchelki' => $wkretyPchelki,
            'meta' => [
                'area_m2' => round($area, 2),
                'perimeter_m' => round(($length + $width) * 2, 2),
                'waste_factor' => $wasteFactor,
                'profile_length_m' => $profileLength,
                'cd60_main_length_m' => round($cd60['main_length_m'], 2),
                'cd60_carrying_length_m' => round($cd60['carrying_length_m'], 2),
                'cd60_total_m' => round($cd60['total_m'], 2),
            ],
        ];
    }

    protected function calculateProfileUd(float $length, float $width, float $profileLength): int
    {
        return (int) ceil(($length + $width) * 2 / $profileLength);
    }

    protected function calculatePlyty(float $area, float $wasteFactor): int
    {
        return (int) ceil(($area * (1 + $wasteFactor)) / self::PANEL_AREA_M2);
    }

    protected function calculateCd60(float $longerSide, float $shorterSide, float $profileLength): array
    {
        $mainRows = (int) ceil($longerSide / self::MAIN_SPACING_M);
        $mainLengthM = $mainRows * $shorterSide;
        $carryingRows = (int) ceil($shorterSide / self::CARRYING_SPACING_M);
        $carryingLengthM = $carryingRows * $longerSide;
        $totalM = ($mainLengthM + $carryingLengthM) * self::CD60_WASTE_FACTOR;
        $pieces = (int) ceil($totalM / $profileLength);
        return [
            'main_rows' => $mainRows,
            'carrying_rows' => $carryingRows,
            'main_length_m' => $mainLengthM,
            'carrying_length_m' => $carryingLengthM,
            'total_m' => $totalM,
            'pieces' => $pieces,
        ];
    }

    protected function calculateWieszaki(float $mainLengthM): int
    {
        return (int) ceil($mainLengthM / self::HANGER_SPACING_M);
    }

    protected function calculatePchelki(int $wieszaki, array $cd60, float $profileLength): int
    {
        $fromHangers = self::PCHELKI_PER_HANGER * $wieszaki;
        $mainPieces = (int) ceil($cd60['main_length_m'] / $profileLength);
        $carryingPieces = (int) ceil($cd60['carrying_length_m'] / $profileLength);
        $longitudinalJoints = max(0, $mainPieces - 1) + max(0, $carryingPieces - 1);
        return $fromHangers + self::PCHELKI_PER_LONGITUDINAL * $longitudinalJoints;
    }
}
