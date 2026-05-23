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
        Schema::create('EquipmentRequestList', function (Blueprint $table) {
            $table->id('ERL_ID'); // Change from increments() to id() for consistency
            $table->unsignedBigInteger('Course_ID');
            $table->unsignedBigInteger('Equip_ID');
            $table->enum('Class_Type', ['Practical', 'Theory', 'Lesson']);
            $table->integer('Expected_Student_Count');
            $table->boolean('Is_Deleted')->default(false);

            // Foreign Keys
            $table->foreign('Course_ID')->references('Course_ID')->on('courses')->onDelete('cascade');
            $table->foreign('Equip_ID')->references('Equip_ID')->on('Equipments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('EquipmentRequestList');
    }
};
