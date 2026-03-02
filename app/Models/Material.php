<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = ['key', 'name', 'search_term'];

    public static function searchTermForKey(string $key): ?string
    {
        $material = static::query()->where('key', $key)->first();
        if (! $material) {
            return null;
        }
        return $material->search_term ?? $material->name;
    }

    public static function searchTermsMap(): array
    {
        return static::query()
            ->get()
            ->mapWithKeys(fn (Material $m) => [$m->key => $m->search_term ?? $m->name])
            ->all();
    }
}
