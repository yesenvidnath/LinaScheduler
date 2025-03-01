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
        Schema::create('Equipment_Types', function (Blueprint $table) {
            $table->increments('Equip_Type_ID');
            $table->string('Equip_Type', 150);
            $table->text('Equip_Type_Discrption');
            $table->boolean('Is_Deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Equipment_Types');
    }
};
