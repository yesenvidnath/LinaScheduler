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
        DB::table('Honorifics')->updateOrInsert(
            ['Honorifics_ID' => 1],
            ['Honorific' => 'Mr.', 'Is_Deleted' => false]
        );

        DB::table('Honorifics')->updateOrInsert(
            ['Honorifics_ID' => 2],
            ['Honorific' => 'Ms.', 'Is_Deleted' => false]
        );

        DB::table('Honorifics')->updateOrInsert(
            ['Honorifics_ID' => 3],
            ['Honorific' => 'Dr.', 'Is_Deleted' => false]
        );

        DB::table('Honorifics')->updateOrInsert(
            ['Honorifics_ID' => 4],
            ['Honorific' => 'Prof.', 'Is_Deleted' => false]
        );
    }
}
