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
        Schema::create('laboratory_types', function (Blueprint $table) {
            $table->id('Lab_Type_ID');
            $table->string('Lab_Type', 100);
            $table->text('Lab_Type_Discription');
            $table->boolean('Is_Deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratory_types');
    }
};
