<?php

namespace App\Services\Ceiling;

/**
 * Serwis kalkulatora sufitów – do wstrzykiwania w Livewire.
 */
class CeilingCalculatorService
{
    public const TYPE_KRZYZOWY = 'krzyzowy';
    public const TYPE_ZWYKLY = 'zwykly';

    public function __construct(
        protected CrossCeilingGridService $crossCeilingGrid
    ) {}

    /**
     * Oblicza materiały na sufit podwieszany.
     *
     * @param  array  $options  waste_factor, profile_length (3.0|4.0)
     * @return array  profile_ud, profile_cd, plyty, wieszaki, laczniki, wkrety, wkrety_pchelki, meta
     */
    public function calculateSuspendedCeiling(string $type, float $length, float $width, array $options = []): array
    {
        $length = max(0.1, (float) $length);
        $width = max(0.1, (float) $width);
        $wasteFactor = isset($options['waste_factor']) ? max(0, min(1, (float) $options['waste_factor'])) : null;
        $profileLength = isset($options['profile_length']) && (float) $options['profile_length'] === 4.0
            ? CrossCeilingGridService::PROFILE_LENGTH_4M
            : CrossCeilingGridService::PROFILE_LENGTH_3M;

        $type = strtolower($type);
        if (!in_array($type, [self::TYPE_KRZYZOWY, self::TYPE_ZWYKLY], true)) {
            throw new \InvalidArgumentException('Nieznany typ sufitu. Dozwolone: krzyzowy, zwykly.');
        }

        $result = $this->crossCeilingGrid->calculate($length, $width, $wasteFactor, $profileLength);
        if ($type === self::TYPE_ZWYKLY) {
            $result['laczniki'] = 0;
            $result['meta']['note'] = 'Sufit zwykły – łączniki krzyżowe nieużywane.';
        }
        return $result;
    }

    public static function materialLabels(): array
    {
        return [
            'profile_ud' => 'Profile przyścienne UD30',
            'profile_cd' => 'Profile nośne CD60',
            'plyty' => 'Płyty gipsowo-kartonowe G-K',
            'wieszaki' => 'Wieszaki (ES/kotwy)',
            'laczniki' => 'Łączniki krzyżowe',
            'wkrety' => 'Wkręty do płyt G-K',
            'wkrety_pchelki' => 'Wkręty (pchełki) do stelaża',
        ];
    }
}
