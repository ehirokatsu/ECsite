<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $param = [
            'name' => 'strawberry',
            'cost' => 100,
            'image' => 'strawberry.jpg',
        ];
        DB::table('products')->insert($param);
    }
}
