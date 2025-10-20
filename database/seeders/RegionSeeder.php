<?php

namespace Database\Seeders;

use App\Models\RegionContentTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Region;
use App\Models\RegionContent;
class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            ['slug' => 'sv',       'name' => 'El Salvador',        'locale' => 'es'],
            ['slug' => 'us',       'name' => 'United States',      'locale' => 'en'],
            ['slug' => 'kr',       'name'=>  'South Korea',        'locale' => 'ko'],
        ];

        foreach ($regions as $r) {
            // 1️⃣ Crea o busca la región
            $region = Region::firstOrCreate(['slug' => $r['slug']], $r);

            // 2️⃣ Crea o busca el contenido base (una fila por región + key)
            $content = RegionContent::firstOrCreate([
                'region_id' => $region->id,
                'key'       => 'about.history',
            ]);

            // 3️⃣ Crea o actualiza la traducción según el idioma de la región
            RegionContentTranslation::updateOrCreate(
                [
                    'content_id' => $content->id,
                    'locale'     => $r['locale'],
                ],
                [
                    'body'  => match($r['locale']) {
                        'ko' => '<p>한국어 전용 HTML (kr)</p>',
                        'en' => '<p>English HTML (us)</p>',
                        default => "<p>HTML específico para {$r['slug']}</p>",
                    },
                ]
            );
        }
    }
}
