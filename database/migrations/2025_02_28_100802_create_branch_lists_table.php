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
        Schema::create('Branch_List', function (Blueprint $table) {
            $table->increments('Branch_List_ID');
            $table->integer('Branch_ID')->unsigned();
            $table->integer('User_ID')->unsigned();
            $table->boolean('Is_Deleted')->default(false);

            $table->foreign('Branch_ID')->references('Branch_ID')->on('Branches');
            $table->foreign('User_ID')->references('User_ID')->on('Users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Branch_List');
    }
};
