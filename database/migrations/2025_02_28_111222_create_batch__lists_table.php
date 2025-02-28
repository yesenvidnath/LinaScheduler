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
            $table->increments('Batch_List_ID');
            $table->integer('User_ID')->unsigned();
            $table->enum('Status', ['Active', 'Ended', 'Suspended']);
            $table->boolean('Is_Deleted')->default(false);

            $table->foreign('User_ID')->references('User_ID')->on('Users');
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
