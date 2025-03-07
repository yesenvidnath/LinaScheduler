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
        Schema::create('users', function (Blueprint $table) {
            $table->id('User_ID');
            $table->unsignedBigInteger('UD_ID');
            $table->unsignedBigInteger('Honorifics_ID');
            $table->string('First_Name', 70);
            $table->string('Last_Name', 70);
            $table->text('User_Discrption');
            $table->enum('Status', ['1', '0', '1*']);
            $table->boolean('Is_Deleted')->default(false);
            $table->foreign('UD_ID')->references('UD_ID')->on('UserDesignations');
            $table->foreign('Honorifics_ID')->references('Honorifics_ID')->on('honorifics');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
