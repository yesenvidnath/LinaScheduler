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
            $table->id('CRB_ID');
            $table->unsignedBigInteger('Room_ID');
            $table->unsignedBigInteger('BookRequest_ID'); // Fixed typo
            $table->text('CRB_Discription');
            $table->boolean('Is_Deleted')->default(false);

            // Foreign Keys
            $table->foreign('Room_ID')->references('Room_ID')->on('Rooms')->onDelete('cascade');
            $table->foreign('BookRequest_ID')->references('BookRequest_ID')->on('BookingRequest')->onDelete('cascade');
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
