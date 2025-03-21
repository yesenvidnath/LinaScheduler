<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'First_Name' => 'John',
                'Last_Name' => 'Doe',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password'),
                'UD_ID' => 1,
                'Honorifics_ID' => 1,
                'User_Discrption' => 'Test user',
                'Status' => '1',
                'Is_Deleted' => false,
            ],
            [
                'First_Name' => 'Jane',
                'Last_Name' => 'Doe',
                'email' => 'jane.doe@example.com',
                'password' => Hash::make('password'),
                'UD_ID' => 2,
                'Honorifics_ID' => 2,
                'User_Discrption' => 'Test user',
                'Status' => '1',
                'Is_Deleted' => false,
            ],
        ]);
    }
}
