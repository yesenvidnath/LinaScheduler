<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('courses')->insert([
            [
                'Course_Name' => 'Mathematics',
                'Course_Discription' => 'Basic Mathematics course',
                'Is_Deleted' => false,
            ],
            [
                'Course_Name' => 'Physics',
                'Course_Discription' => 'Basic Physics course',
                'Is_Deleted' => false,
            ],
        ]);
    }
}
