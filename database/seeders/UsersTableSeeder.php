<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $param = [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => '$2y$10$FNWNl5lXgLP9I9xIJ1tR4OGhZz3jPbTDDHcdB5IVSSOv1Fs4RP5ay',
            'postal_code' => '1001000',
            'address_1' => '東京都',
            'address_2' => '中央区',
            'address_3' => 'テスト市',
            'phone_number' => '09012345678',
            'role' => '1',
        ];
        DB::table('users')->insert($param);
    }
}
