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
        Schema::create('students', function (Blueprint $table) {
            $table->id('Student_ID'); // Auto-increment primary key
            $table->unsignedBigInteger('User_ID'); // Foreign key to Users
            $table->boolean('Is_Deleted')->default(false);
            $table->enum('Status', ['1', '0', '1*']);
            // Foreign Key Constraints
            $table->foreign('User_ID')->references('User_ID')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
