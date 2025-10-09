<?php

namespace Database\Seeders;

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
            ['slug' => 'sv',       'name' => 'El Salvador',       'locale' => 'es'],
            ['slug' => 'us',       'name' => 'United States',     'locale' => 'en'],
            ['slug' => 'latin-es', 'name' => 'Latinoamérica (ES)','locale' => 'es'],
        ];
        foreach ($regions as $r) {
            $region = Region::firstOrCreate(['slug' => $r['slug']], $r);

            // Ejemplo de un contenido por región:
            RegionContent::updateOrCreate(
                ['region_id' => $region->id, 'key' => 'about.history'],
                ['title' => 'Nuestra Historia', 'body' => '<p>HTML específico para '.$r['slug'].'</p>', 'status' => 'published']
            );
        }
    }
}
