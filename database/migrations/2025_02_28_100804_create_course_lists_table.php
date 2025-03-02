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
        Schema::create('course_lists', function (Blueprint $table) {
            $table->id('Course_List_ID');
            $table->unsignedBigInteger('Course_ID');
            $table->unsignedBigInteger('User_ID');
            $table->boolean('Is_Deleted')->default(false);
            $table->foreign('Course_ID')->references('Course_ID')->on('courses');
            $table->foreign('User_ID')->references('User_ID')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_lists');
    }
};
