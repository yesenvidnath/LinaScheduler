<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('User_Work_Days', function (Blueprint $table) {
            $table->id('UWD_ID');
            $table->unsignedBigInteger('User_ID');
            $table->unsignedBigInteger('Work_Mode_ID');
            $table->enum('Day_Of_Week', [
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
                'Sunday',
            ]);
            $table->time('Work_Start_Time');
            $table->time('Work_End_Time');
            $table->enum('Status', ['Active', 'Inactive'])->default('Active');
            $table->boolean('Is_Deleted')->default(false);

            $table->foreign('User_ID')->references('User_ID')->on('users')->onDelete('cascade');
            $table->foreign('Work_Mode_ID')->references('Work_Mode_ID')->on('Work_Modes')->onDelete('cascade');
            $table->index(['User_ID', 'Day_Of_Week', 'Is_Deleted']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('User_Work_Days');
    }
};
