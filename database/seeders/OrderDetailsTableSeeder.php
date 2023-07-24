<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class OrderDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $param = [
            'order_id' => 1,
            'product_id' => 1,
            'name' => 'strawberry',
            'cost' => 100,
            'quantity' => 1,
        ];
        DB::table('order_details')->insert($param);

        $param = [
            'order_id' => 1,
            'product_id' => 2,
            'name' => 'carrot',
            'cost' => 200,
            'quantity' => 1,
        ];
        DB::table('order_details')->insert($param);
    }
}
