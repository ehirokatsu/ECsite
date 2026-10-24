<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RenderDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (! config('demo.enabled')) {
            throw new \RuntimeException('RenderDemoSeeder requires DEMO_MODE=true.');
        }

        File::ensureDirectoryExists(storage_path('app/public'));
        foreach ([['デモ商品：いちご', 100, 'demo-strawberry.svg', '#e84d65'],
                  ['デモ商品：にんじん', 200, 'demo-carrot.svg', '#ed923a']] as [$name, $cost, $image, $color]) {
            File::put(storage_path('app/public/'.$image),
                '<svg xmlns="http://www.w3.org/2000/svg" width="480" height="320" viewBox="0 0 480 320">'.
                '<rect width="480" height="320" fill="#f8fafc"/><circle cx="240" cy="140" r="72" fill="'.$color.'"/>'.
                '<text x="240" y="270" text-anchor="middle" font-size="28" fill="#334155">DEMO</text></svg>');
            if (! DB::table('products')->where('image', $image)->exists()) {
                DB::table('products')->insert([
                    'name' => $name, 'cost' => $cost, 'image' => $image,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        }
    }
}
