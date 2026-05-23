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
        Schema::dropIfExists('Lecture_Allocations');

        Schema::create('Lecture_Allocations', function (Blueprint $table) {
            $table->id('LA_ID');
            $table->unsignedBigInteger('Lecturer_User_ID');
            $table->unsignedBigInteger('Batch_ID');
            $table->unsignedBigInteger('Course_ID')->nullable();
            $table->unsignedBigInteger('Cls_ID')->nullable();
            $table->date('Allocation_Date');
            $table->enum('Day_Of_Week', [
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
                'Sunday',
            ]);
            $table->time('Session_Start_Time');
            $table->time('Session_End_Time');
            $table->enum('Session_Type', ['Theory', 'Practical', 'Examination', 'Viva']);
            $table->boolean('Is_Cancelled')->default(false);
            $table->boolean('Is_Additional_Working_Situation')->default(false);
            $table->text('Lecturer_Comment')->nullable();
            $table->text('Coordinator_Comment')->nullable();
            $table->boolean('Is_Deleted')->default(false);

            $table->foreign('Lecturer_User_ID')->references('User_ID')->on('users')->onDelete('cascade');
            $table->foreign('Batch_ID')->references('Batch_ID')->on('Batches')->onDelete('cascade');
            $table->foreign('Course_ID')->references('Course_ID')->on('courses')->onDelete('set null');
            $table->foreign('Cls_ID')->references('Cls_ID')->on('classes')->onDelete('set null');
            $table->index(['Lecturer_User_ID', 'Allocation_Date', 'Is_Cancelled', 'Is_Deleted'], 'la_lecturer_date_status_idx');
            $table->index(['Batch_ID', 'Allocation_Date', 'Is_Cancelled', 'Is_Deleted'], 'la_batch_date_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Lecture_Allocations');
    }
};
