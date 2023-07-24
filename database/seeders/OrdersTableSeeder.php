<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $param = [
            'ordered_on' => '2023-07-24 12:38:07',
            'name' => 'test',
            'user_id' => 1,
            'name' => 'test',
            'email' => 'test@test.com',
            'postal_code' => '1001000',
            'address_1' => '東京都',
            'address_2' => '中央区',
            'address_3' => 'テスト市',
            'phone_number' => '09012345678',
        ];
        DB::table('orders')->insert($param);
    }
}
