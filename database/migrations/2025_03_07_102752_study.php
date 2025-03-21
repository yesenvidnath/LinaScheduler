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
        Schema::create('studies', function (Blueprint $table) {
            $table->id('Study_ID');
            $table->unsignedBigInteger('Room_ID');
            $table->string('Study_Number', 50);
            $table->text('Study_Discription');
            $table->boolean('Is_Deleted')->default(false);

            // Foreign Key
            $table->foreign('Room_ID')->references('Room_ID')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studies');
    }
};
