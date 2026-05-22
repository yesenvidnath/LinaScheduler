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
        Schema::create('BookingRequest', function (Blueprint $table) {
            $table->id('BookRequest_ID');
            $table->unsignedBigInteger('Course_ID');
            $table->unsignedBigInteger('Batch_ID');
            $table->unsignedBigInteger('User_ID');
            $table->unsignedBigInteger('ERL_ID')->nullable(); // Keep it unsignedBigInteger

            $table->enum('Class_Type', ['Practical', 'Theory', 'Lesson']);
            $table->integer('Expected_Student_Count');
            $table->timestamp('Class_Start_Time')->nullable();
            $table->timestamp('Class_End_Time')->nullable();
            $table->enum('Status', ['Confirmed', 'Pending', 'Rejected']);
            $table->boolean('Is_Deleted')->default(false);

            // Foreign Keys
            $table->foreign('Course_ID')->references('Course_ID')->on('courses')->onDelete('cascade');
            $table->foreign('Batch_ID')->references('Batch_ID')->on('Batches')->onDelete('cascade');
            $table->foreign('User_ID')->references('User_ID')->on('users')->onDelete('cascade');
            $table->foreign('ERL_ID')->references('ERL_ID')->on('EquipmentRequestList')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('BookingRequest');
    }
};
