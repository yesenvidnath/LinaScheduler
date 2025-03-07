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
        Schema::create('flows', function (Blueprint $table) {
            $table->id('Fl_ID');
            $table->unsignedBigInteger('Branch_ID');
            $table->string('Fl_Name', 100);
            $table->text('Fl_Discription');
            $table->boolean('Is_Deleted')->default(false);

            // Foreign Key Constraint
            $table->foreign('Branch_ID')->references('Branch_ID')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flows');
    }
};
