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
        Schema::create('Batch_List', function (Blueprint $table) {
            $table->id('Batch_List_ID');
            $table->unsignedBigInteger('Batch_ID');
            $table->unsignedBigInteger('User_ID');
            $table->unsignedBigInteger('Branch_ID');
            $table->enum('Status', ['Active', 'Ended', 'Suspended']);
            $table->boolean('Is_Deleted')->default(false);

            $table->foreign('Batch_ID')->references('Batch_ID')->on('Batches');
            $table->foreign('User_ID')->references('User_ID')->on('Users');
            $table->foreign('Branch_ID')->references('Branch_ID')->on('Branches');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Batch_List');
    }
};
