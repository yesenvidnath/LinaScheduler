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
        Schema::create('Branches', function (Blueprint $table) {
            $table->increments('Branch_ID');
            $table->string('Branch_Name', 100);
            $table->text('Branch_Discription');
            $table->boolean('Is_Deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Branches');
    }
};
