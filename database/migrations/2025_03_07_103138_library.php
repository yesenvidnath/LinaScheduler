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
        Schema::create('libraries', function (Blueprint $table) {
            $table->id('Lib_ID');
            $table->unsignedBigInteger('Room_ID');
            $table->string('Lib_Number', 50);
            $table->text('Lib_Discription');
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
        Schema::dropIfExists('libraries');
    }
};
