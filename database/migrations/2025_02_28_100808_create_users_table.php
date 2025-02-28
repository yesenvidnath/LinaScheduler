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
        Schema::create('Users', function (Blueprint $table) {
            $table->increments('User_ID');
            $table->integer('UD_ID')->unsigned();
            $table->integer('Batch_List_ID')->unsigned();
            $table->integer('Honorifics_ID')->unsigned();
            $table->string('First_Name');
            $table->string('Last_Name');
            $table->text('User_Discrption');
            $table->enum('Status', ['Active', 'Ended', 'Suspended']);
            $table->boolean('Is_Deleted')->default(false);

            $table->foreign('UD_ID')->references('UD_ID')->on('UserDesignations');
            $table->foreign('Batch_List_ID')->references('Batch_List_ID')->on('Batch_List');
            $table->foreign('Honorifics_ID')->references('Honorifics_ID')->on('Honorifics');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Users');
    }
};
