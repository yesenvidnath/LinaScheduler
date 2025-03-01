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
            $table->increments('BookReqest_ID');
            $table->integer('Course_ID')->unsigned();
            $table->integer('Batch_ID')->unsigned();
            $table->integer('User_ID')->unsigned();
            $table->integer('ERL_ID')->unsigned()->nullable();
            $table->enum('Class_Type', ['Practical', 'Lession']);
            $table->integer('Expected_Student_Count');
            $table->timestamp('Class_Start_Time');
            $table->timestamp('Class_End_Time');
            $table->enum('Status', ['Confirmed', 'Pending', 'Rejected']);
            $table->boolean('Is_Deleted')->default(false);

            $table->foreign('Course_ID')->references('Course_ID')->on('Courses');
            $table->foreign('Batch_ID')->references('Batch_ID')->on('Batches');
            $table->foreign('User_ID')->references('User_ID')->on('Users');
            $table->foreign('ERL_ID')->references('ERL_ID')->on('EquipmentRequestList');
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
