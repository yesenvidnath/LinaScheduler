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
        DB::table('UserDesignations')->updateOrInsert(
            ['UD_ID' => 1],
            ['Designation' => 'Admin', 'Is_Deleted' => false]
        );

        DB::table('UserDesignations')->updateOrInsert(
            ['UD_ID' => 2],
            ['Designation' => 'Professor', 'Is_Deleted' => false]
        );

        DB::table('UserDesignations')->updateOrInsert(
            ['UD_ID' => 3],
            ['Designation' => 'Assistant Professor', 'Is_Deleted' => false]
        );
    }
}
