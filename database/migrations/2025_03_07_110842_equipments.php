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
        Schema::create('Equipments', function (Blueprint $table) {
            $table->id('Equip_ID');
            $table->unsignedBigInteger('Equip_Type_ID'); // Ensure unsignedBigInteger to match Equipment_Types
            $table->text('Equip_Discrption');
            $table->enum('Equip_Userbility_Status', ['1', '0', '1*']);
            $table->enum('Is_Booked', ['1', '0', '1*']);
            $table->boolean('Is_Deleted')->default(false);

            // Foreign Key Constraint
            $table->foreign('Equip_Type_ID')->references('Equip_Type_ID')->on('Equipment_Types')->onDelete('cascade'); // If an equipment type is deleted, related equipment is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Equipments');
    }
};
