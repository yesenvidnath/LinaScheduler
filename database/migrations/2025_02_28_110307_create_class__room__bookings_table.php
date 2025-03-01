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
        Schema::create('Class_Room_Bookings', function (Blueprint $table) {
            $table->increments('CRB_ID');
            $table->integer('Room_ID')->unsigned();
            $table->integer('BookReqest_ID')->unsigned();
            $table->text('CRB_Discription');
            $table->boolean('Is_Deleted')->default(false);

            $table->foreign('Room_ID')->references('Room_ID')->on('Rooms');
            $table->foreign('BookReqest_ID')->references('BookReqest_ID')->on('BookingRequest');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Class_Room_Bookings');
    }
};
