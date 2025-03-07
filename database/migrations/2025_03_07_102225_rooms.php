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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id('Room_ID');
            $table->unsignedBigInteger('Fl_ID');
            $table->string('Room_Number', 200);
            $table->text('Room_Discrption');
            $table->enum('Room_Availability', ['0', '1', '1*']);
            $table->enum('Room_Type', ['Library', 'Class', 'Laboratory', 'StudyArea']);
            $table->integer('Max_Student_Count');
            $table->integer('Max_Chair_Count');
            $table->integer('Max_Power_Outlets');
            $table->integer('Max_Table_Count');
            $table->boolean('Is_WhiteBoard_Avilable')->default(false);
            $table->boolean('Is_Projector_Avilable')->default(false);
            $table->boolean('Is_Smart_board_Avilable')->default(false);
            $table->boolean('Is_Deleted')->default(false);
            $table->timestamps();
            $table->foreign('Fl_ID')->references('Fl_ID')->on('flows');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
