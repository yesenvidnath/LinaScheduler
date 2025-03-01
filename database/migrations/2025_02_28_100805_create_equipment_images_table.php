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
        Schema::create('Equipment_Images', function (Blueprint $table) {
            $table->increments('EQI_ID');
            $table->integer('Equip_ID')->unsigned();
            $table->string('EQI_Image', 255);
            $table->text('EQI_Discription');
            $table->boolean('Is_Deleted')->default(false);

            $table->foreign('Equip_ID')->references('Equip_ID')->on('Equipments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Equipment_Images');
    }
};
