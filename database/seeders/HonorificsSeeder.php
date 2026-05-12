<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HonorificsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Honorifics')->insert([
            [
                'Honorific' => 'Mr.',
                'Is_Deleted' => false,
            ],
            [
                'Honorific' => 'Ms.',
                'Is_Deleted' => false,
            ],
            [
                'Honorific' => 'Dr.',
                'Is_Deleted' => false,
            ],
            [
                'Honorific' => 'Prof.',
                'Is_Deleted' => false,
            ],
        ]);
    }
}
