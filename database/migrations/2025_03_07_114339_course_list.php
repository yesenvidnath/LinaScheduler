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
            $table->id('Course_List_ID'); // Auto-increment primary key
            $table->unsignedBigInteger('Course_ID'); // Foreign key to Courses
            $table->unsignedBigInteger('User_ID'); // Foreign key to Users
            $table->boolean('Is_Deleted')->default(false);

            // Foreign Key Constraints
            $table->foreign('Course_ID')->references('Course_ID')->on('courses')->onDelete('cascade');
            $table->foreign('User_ID')->references('User_ID')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_lists');
        Schema::dropIfExists('course_list');
    }
};
