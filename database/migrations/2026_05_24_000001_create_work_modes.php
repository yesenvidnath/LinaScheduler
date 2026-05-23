<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Work_Modes', function (Blueprint $table) {
            $table->id('Work_Mode_ID');
            $table->string('Work_Mode_Name', 50);
            $table->text('Work_Mode_Description')->nullable();
            $table->boolean('Is_Deleted')->default(false);
        });

        DB::table('Work_Modes')->insert([
            [
                'Work_Mode_Name' => 'Onsite',
                'Work_Mode_Description' => 'User works from the campus or assigned physical location.',
                'Is_Deleted' => false,
            ],
            [
                'Work_Mode_Name' => 'Visiting',
                'Work_Mode_Description' => 'User visits for scheduled classes or assigned sessions.',
                'Is_Deleted' => false,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Work_Modes');
    }
};
