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
        Schema::create('Branch_List', function (Blueprint $table) {
            $table->id('Branch_List_ID');
            $table->unsignedBigInteger('Branch_ID'); // Ensure unsignedBigInteger to match Branches
            $table->unsignedBigInteger('User_ID'); // Ensure unsignedBigInteger to match users
            $table->boolean('Is_Deleted')->default(false);

            // Foreign Key Constraints
            $table->foreign('Branch_ID')->references('Branch_ID')->on('Branches')->onDelete('cascade');
            $table->foreign('User_ID')->references('User_ID')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Branch_List');
    }
};
