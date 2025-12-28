<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prace Malarskie
        $malowanie = Category::where('slug', 'malowanie')->first();
        if ($malowanie) {
            $services = [
                ['name' => 'Gruntowanie ścian', 'unit' => 'm²', 'suggested_price' => 18.00, 'order' => 1],
                ['name' => 'Gruntowanie sufitów', 'unit' => 'm²', 'suggested_price' => 12.00, 'order' => 2],
                ['name' => 'Aplikacja gładzi szpachlowej. 1 warstwa', 'unit' => 'm²', 'suggested_price' => 8.00, 'order' => 3],
                ['name' => 'Aplikacja gładzi szpachlowej. 2 warstwy', 'unit' => 'm²', 'suggested_price' => 25.00, 'order' => 4],
                ['name' => 'Aplikacja farby gruntującej', 'unit' => 'm²', 'suggested_price' => 22.00, 'order' => 5],
                ['name' => 'Aplikacja akryli', 'unit' => 'm²', 'suggested_price' => 20.00, 'order' => 6],
                ['name' => 'Dwukrotne malowanie ścian', 'unit' => 'm²', 'suggested_price' => 35.00, 'order' => 7],
                ['name' => 'Dwukrotne malowanie sufitów', 'unit' => 'm²', 'suggested_price' => 80.00, 'order' => 8],
            ];
            foreach ($services as $service) {
                Service::create(array_merge($service, ['category_id' => $malowanie->id]));
            }
        }

        // Prace Glazurnicze
        $glazura = Category::where('slug', 'glazura')->first();
        if ($glazura) {
            $services = [
                ['name' => 'Układanie płytek podłogowych (format do 30x30)', 'unit' => 'm²', 'suggested_price' => 50.00, 'order' => 1],
                ['name' => 'Układanie płytek podłogowych (format powyżej 30x30)', 'unit' => 'm²', 'suggested_price' => 60.00, 'order' => 2],
                ['name' => 'Układanie płytek w łazience', 'unit' => 'm²', 'suggested_price' => 65.00, 'order' => 3],
                ['name' => 'Fugowanie płytek', 'unit' => 'm²', 'suggested_price' => 15.00, 'order' => 4],
                ['name' => 'Cięcie płytek pod kątem 45 stopni', 'unit' => 'mb', 'suggested_price' => 20.00, 'order' => 5],
                ['name' => 'Aplikacja silikonu', 'unit' => 'mb', 'suggested_price' => 30.00, 'order' => 6],
            ];
            foreach ($services as $service) {
                Service::create(array_merge($service, ['category_id' => $glazura->id]));
            }
        }

        // Glazura w łazience/Armatura
        $glazuraLazienka = Category::where('slug', 'glazura-w-lazience-armatura')->first();
        if ($glazuraLazienka) {
            $services = [
                ['name' => 'Układanie płytek ściennych (format do 30x30, nie rektyfikowane)', 'unit' => 'm²', 'suggested_price' => 50.00, 'order' => 1],
                ['name' => 'Układanie płytek ściennych (format od 30x30 do 30x60, nie rektyfikowane)', 'unit' => 'm²', 'suggested_price' => 50.00, 'order' => 2],
                ['name' => 'Układanie płytek ściennych (format od 60x60, rektyfikowane, fuga do 2mm)', 'unit' => 'm²', 'suggested_price' => 50.00, 'order' => 3],
                ['name' => 'Cięcie płytek pod kątem 45 stopni', 'unit' => 'mb', 'suggested_price' => 20.00, 'order' => 4],
                ['name' => 'Otwory w płytkach', 'unit' => 'szt', 'suggested_price' => 30.00, 'order' => 5],
                ['name' => 'Aplikacja silikonu', 'unit' => 'mb', 'suggested_price' => 30.00, 'order' => 6],
            ];
            foreach ($services as $service) {
                Service::create(array_merge($service, ['category_id' => $glazuraLazienka->id]));
            }
        }

        // Prace Elektryczne
        $elektryka = Category::where('slug', 'elektryka')->first();
        if ($elektryka) {
            $services = [
                ['name' => 'Montaż gniazdka jednofazowego', 'unit' => 'szt', 'suggested_price' => 80.00, 'order' => 1],
                ['name' => 'Montaż gniazdka trójfazowego', 'unit' => 'szt', 'suggested_price' => 120.00, 'order' => 2],
                ['name' => 'Montaż włącznika światła', 'unit' => 'szt', 'suggested_price' => 60.00, 'order' => 3],
                ['name' => 'Montaż punktu świetlnego', 'unit' => 'szt', 'suggested_price' => 100.00, 'order' => 4],
                ['name' => 'Montaż lampy sufitowej', 'unit' => 'szt', 'suggested_price' => 120.00, 'order' => 5],
                ['name' => 'Montaż lampy ściennej', 'unit' => 'szt', 'suggested_price' => 90.00, 'order' => 6],
                ['name' => 'Montaż taśmy LED', 'unit' => 'mb', 'suggested_price' => 40.00, 'order' => 7],
                ['name' => 'Montaż listwy LED', 'unit' => 'mb', 'suggested_price' => 50.00, 'order' => 8],
                ['name' => 'Montaż oprawy LED w suficie', 'unit' => 'szt', 'suggested_price' => 80.00, 'order' => 9],
                ['name' => 'Montaż dzwonka do drzwi', 'unit' => 'szt', 'suggested_price' => 150.00, 'order' => 10],
            ];
            foreach ($services as $service) {
                Service::create(array_merge($service, ['category_id' => $elektryka->id]));
            }
        }

        // Prace Hydrauliczne
        $hydraulika = Category::where('slug', 'hydraulika')->first();
        if ($hydraulika) {
            $services = [
                ['name' => 'Montaż umywalki', 'unit' => 'szt', 'suggested_price' => 200.00, 'order' => 1],
                ['name' => 'Montaż baterii umywalkowej', 'unit' => 'szt', 'suggested_price' => 150.00, 'order' => 2],
                ['name' => 'Montaż wanny', 'unit' => 'szt', 'suggested_price' => 400.00, 'order' => 3],
                ['name' => 'Montaż kabiny prysznicowej', 'unit' => 'szt', 'suggested_price' => 350.00, 'order' => 4],
                ['name' => 'Montaż baterii prysznicowej', 'unit' => 'szt', 'suggested_price' => 180.00, 'order' => 5],
                ['name' => 'Montaż toalety', 'unit' => 'szt', 'suggested_price' => 300.00, 'order' => 6],
                ['name' => 'Podłączenie pralki', 'unit' => 'szt', 'suggested_price' => 180.00, 'order' => 7],
                ['name' => 'Podłączenie zmywarki', 'unit' => 'szt', 'suggested_price' => 180.00, 'order' => 8],
                ['name' => 'Montaż grzejnika łazienkowego', 'unit' => 'szt', 'suggested_price' => 250.00, 'order' => 9],
                ['name' => 'Montaż syfonu', 'unit' => 'szt', 'suggested_price' => 100.00, 'order' => 10],
            ];
            foreach ($services as $service) {
                Service::create(array_merge($service, ['category_id' => $hydraulika->id]));
            }
        }

        // Sucha Zabudowa
        $suchaZabudowa = Category::where('slug', 'sucha-zabudowa')->first();
        if ($suchaZabudowa) {
            $services = [
                ['name' => 'Montaż ścian z płyt G-K (jedna warstwa)', 'unit' => 'm²', 'suggested_price' => 40.00, 'order' => 1],
                ['name' => 'Montaż ścian z płyt G-K (dwie warstwy)', 'unit' => 'm²', 'suggested_price' => 70.00, 'order' => 2],
                ['name' => 'Montaż sufitów podwieszanych', 'unit' => 'm²', 'suggested_price' => 35.00, 'order' => 3],
                ['name' => 'Montaż sufitów podwieszanych z oświetleniem', 'unit' => 'm²', 'suggested_price' => 50.00, 'order' => 4],
                ['name' => 'Montaż listew maskujących', 'unit' => 'mb', 'suggested_price' => 15.00, 'order' => 5],
                ['name' => 'Montaż profili stalowych', 'unit' => 'mb', 'suggested_price' => 12.00, 'order' => 6],
                ['name' => 'Montaż płyt G-K na suficie', 'unit' => 'm²', 'suggested_price' => 45.00, 'order' => 7],
                ['name' => 'Montaż płyt G-K na ścianach', 'unit' => 'm²', 'suggested_price' => 40.00, 'order' => 8],
                ['name' => 'Montaż narożników', 'unit' => 'mb', 'suggested_price' => 20.00, 'order' => 9],
                ['name' => 'Montaż ścianki działowej', 'unit' => 'm²', 'suggested_price' => 55.00, 'order' => 10],
            ];
            foreach ($services as $service) {
                Service::create(array_merge($service, ['category_id' => $suchaZabudowa->id]));
            }
        }

        // Prace Stolarskie
        $stolarka = Category::where('slug', 'stolarka')->first();
        if ($stolarka) {
            $services = [
                ['name' => 'Montaż drzwi wewnętrznych', 'unit' => 'szt', 'suggested_price' => 200.00, 'order' => 1],
                ['name' => 'Montaż drzwi zewnętrznych', 'unit' => 'szt', 'suggested_price' => 350.00, 'order' => 2],
                ['name' => 'Montaż okien PCV', 'unit' => 'szt', 'suggested_price' => 250.00, 'order' => 3],
                ['name' => 'Montaż blatu kuchennego', 'unit' => 'mb', 'suggested_price' => 200.00, 'order' => 4],
            ];
            foreach ($services as $service) {
                Service::create(array_merge($service, ['category_id' => $stolarka->id]));
            }
        }
    }
}
