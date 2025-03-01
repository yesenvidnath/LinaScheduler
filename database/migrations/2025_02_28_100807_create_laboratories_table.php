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
        Schema::create('laboratories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('lab_type_id');
            $table->string('lab_number', 200);
            $table->text('lab_description');
            $table->boolean('is_deleted')->default(false);

            $table->foreign('room_id')->references('id')->on('rooms');
            $table->foreign('lab_type_id')->references('id')->on('laboratory_types');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratories');
    }
};
