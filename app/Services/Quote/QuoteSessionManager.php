<?php

namespace App\Services\Quote;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

/**
 * Session-based storage for multi-step quote items.
 * Stores quote_items as array; each item: id, category, room_name, parameters, materials.
 */
class QuoteSessionManager
{
    private const SESSION_KEY = 'quote_items';

    public function getItems(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    /**
     * Add one quote item. Structure:
     *   id => uuid (optional, generated if missing),
     *   category => string (e.g. 'Sufit Podwieszany'),
     *   room_name => string,
     *   parameters => array (input data),
     *   materials => array (key => int|float from calculator).
     */
    public function addItem(array $item): void
    {
        $item['id'] = $item['id'] ?? (string) Str::uuid();
        $items = $this->getItems();
        $items[] = $item;
        Session::put(self::SESSION_KEY, $items);
    }

    public function removeItem(string $id): void
    {
        $items = array_values(array_filter($this->getItems(), fn ($i) => ($i['id'] ?? '') !== $id));
        Session::put(self::SESSION_KEY, $items);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /**
     * Sum materials across all items by key (int/float).
     * Keys like 'meta' or nested arrays are skipped; only numeric values are summed.
     */
    public function aggregateMaterials(): array
    {
        $aggregated = [];
        foreach ($this->getItems() as $item) {
            $materials = $item['materials'] ?? [];
            foreach ($materials as $key => $value) {
                if ($key === 'meta' || is_array($value)) {
                    continue;
                }
                if (is_numeric($value)) {
                    $v = (float) $value;
                    $aggregated[$key] = ($aggregated[$key] ?? 0) + $v;
                }
            }
        }
        return $aggregated;
    }
}
