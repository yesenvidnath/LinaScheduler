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
        Schema::create('Rooms', function (Blueprint $table) {
            $table->increments('Room_ID');
            $table->integer('Fl_ID')->unsigned();
            $table->string('Room_Number', 100);
            $table->text('Room_Discrption');
            $table->enum('Room_Availability', ['Available', 'Not Available']);
            $table->enum('Room_Type', ['Lecture', 'Lab']);
            $table->integer('Max_Student_Count');
            $table->integer('Max_Chair_Count');
            $table->integer('Max_Power_Outlets');
            $table->integer('Max_Table_Count');
            $table->boolean('Is_WhiteBoard_Avilable');
            $table->boolean('Is_Projector_Avilable');
            $table->boolean('Is_Smart_board_Avilable');
            $table->boolean('Is_Deleted')->default(false);

            $table->foreign('Fl_ID')->references('Fl_ID')->on('Flows');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Rooms');
    }
};
