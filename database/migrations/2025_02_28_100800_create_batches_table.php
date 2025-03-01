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
        Schema::create('Batches', function (Blueprint $table) {
            $table->increments('Batch_ID');
            $table->string('Batch_Name', 100);
            $table->integer('Batch_Student_Count');
            $table->text('Batch_Discription');
            $table->enum('Status', ['1', '0', '1*']);
            $table->boolean('Is_Deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Batches');
    }
};
