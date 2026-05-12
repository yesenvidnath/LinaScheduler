<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserDesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('UserDesignations')->insert([
            [
                'Designation' => 'Professor',
                'Is_Deleted' => false,
            ],
            [
                'Designation' => 'Assistant Professor',
                'Is_Deleted' => false,
            ],
        ]);
    }
}
