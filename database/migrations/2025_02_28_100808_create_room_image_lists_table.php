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
        Schema::create('Room_Image_Lists', function (Blueprint $table) {
            $table->increments('RIL_ID');
            $table->integer('Room_ID')->unsigned();
            $table->string('RIL_Image', 255);
            $table->text('RIL_Discription');
            $table->boolean('Is_Deleted')->default(false);

            $table->foreign('Room_ID')->references('Room_ID')->on('Rooms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Room_Image_Lists');
    }
};
