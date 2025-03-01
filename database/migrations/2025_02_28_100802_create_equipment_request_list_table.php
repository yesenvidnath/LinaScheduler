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
            $table->increments('ERL_ID');
            $table->integer('Course_ID')->unsigned();
            $table->integer('Equip_ID')->unsigned();
            $table->enum('Class_Type', ['Practical', 'Lession']);
            $table->integer('Expected_Student_Count');
            $table->boolean('Is_Deleted')->default(false);

            $table->foreign('Course_ID')->references('Course_ID')->on('Courses');
            $table->foreign('Equip_ID')->references('Equip_ID')->on('Equipments');
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
