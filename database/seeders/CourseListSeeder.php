<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('course_lists')->insert([
            [
                'Course_ID' => 1,
                'User_ID' => 1,
                'Is_Deleted' => false,
            ],
            [
                'Course_ID' => 2,
                'User_ID' => 2,
                'Is_Deleted' => false,
            ],
        ]);
    }
}
