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
        Schema::create('Flows', function (Blueprint $table) {
            $table->increments('Fl_ID');
            $table->integer('Branch_ID')->unsigned();
            $table->foreign('Branch_ID')->references('id')->on('Branches')->onDelete('cascade');
            $table->string('Fl_Name', 100);
            $table->text('Fl_Discription');
            $table->boolean('Is_Deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Flows');
    }
};
