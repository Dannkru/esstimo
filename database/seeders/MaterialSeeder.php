<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Services\BathroomTech\BathroomTechCalculatorService;
use App\Services\Ceiling\CeilingCalculatorService;
use App\Services\Floor\FloorCalculatorService;
use App\Services\Insulation\InsulationCalculatorService;
use App\Services\WallFinishing\WallFinishingCalculatorService;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    private static function searchTermOverrides(): array
    {
        return [
            'profile_ud' => 'Profil UD30',
            'profile_cd' => 'Profil CD60',
            'plyty' => 'płyta gipsowo kartonowa',
            'wieszaki' => 'wieszak do płyt gipsowych',
            'laczniki' => 'łącznik krzyżowy sufit',
            'wkrety' => 'wkręty do płyt gipsowych',
            'wkrety_pchelki' => 'wkręty pchełki stelaż',
            'klej_worki_25kg' => 'klej do płytek',
            'fuga_kg' => 'fuga do płytek',
            'wylewka_worki_25kg' => 'wylewka samopoziomująca',
            'grunt_l' => 'grunt uniwersalny',
            'tasma_dylatacyjna_mb' => 'taśma dylatacyjna',
            'system_poziomowania_klipsy_szt' => 'system poziomowania płytek',
            'glad_startowa_worki_20kg' => 'gładź szpachlowa startowa',
            'glad_finisz_worki_20kg' => 'gładź szpachlowa finiszowa',
            'grunt_banki_5l' => 'grunt malarski',
            'farba_wiadra_10l' => 'farba emulsyjna',
            'papier_scierny_szt' => 'papier ścierny',
            'folia_szt' => 'folia malarska',
            'tasma_rolki_szt' => 'taśma malarska',
            'folia_plynna_kg' => 'folia w płynie łazienka',
            'folia_5kg_szt' => 'folia w płynie hydroizolacja',
            'folia_15kg_szt' => 'folia w płynie hydroizolacja',
            'tasma_mb' => 'taśma uszczelniająca łazienka',
            'tasma_rolki_10mb_szt' => 'taśma uszczelniająca',
            'tasma_rolki_50mb_szt' => 'taśma uszczelniająca',
            'mankety_szt' => 'mankiety uszczelniające',
            'silikon_szt' => 'silikon sanitarny',
            'welna_warstwa1_m2' => 'wełna mineralna 15 cm',
            'welna_warstwa2_m2' => 'wełna mineralna 10 cm',
            'welna_warstwa1_rolki_approx_szt' => 'wełna mineralna rolka',
            'welna_warstwa2_rolki_approx_szt' => 'wełna mineralna rolka',
            'folia_paro_m2' => 'folia paroizolacyjna',
            'folia_paro_rolki_100_szt' => 'folia paroizolacyjna',
            'tasma_do_folii_mb' => 'taśma do folii paro',
            'tasma_do_folii_rolki_25_szt' => 'taśma do folii',
            'tasma_do_folii_rolki_50_szt' => 'taśma do folii',
            'grzybki_szt' => 'wieszaki grzybki ocieplenie',
            'profile_cd60_szt' => 'profil CD60',
        ];
    }

    public function run(): void
    {
        $overrides = self::searchTermOverrides();
        $labels = array_merge(
            CeilingCalculatorService::materialLabels(),
            FloorCalculatorService::floorResultLabels(),
            WallFinishingCalculatorService::resultLabels(),
            BathroomTechCalculatorService::hydroizolacjaLabels(),
            InsulationCalculatorService::ocieplenieLabels()
        );
        foreach ($labels as $key => $name) {
            Material::updateOrCreate(
                ['key' => $key],
                ['name' => $name, 'search_term' => $overrides[$key] ?? null]
            );
        }
    }
}
