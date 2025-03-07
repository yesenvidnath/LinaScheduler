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
        Schema::create('laboratories', function (Blueprint $table) {
            $table->id('Lab_ID');
            $table->unsignedBigInteger('Room_ID');
            $table->unsignedBigInteger('Lab_Type_ID');
            $table->string('Lab_Number', 50);
            $table->integer('Lab_Equipment_Count');
            $table->text('Lab_Discription');
            $table->boolean('Is_Deleted')->default(false);

            // Foreign Keys
            $table->foreign('Room_ID')->references('Room_ID')->on('rooms')->onDelete('cascade');
            $table->foreign('Lab_Type_ID')->references('Lab_Type_ID')->on('laboratory_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratories');
    }
};
