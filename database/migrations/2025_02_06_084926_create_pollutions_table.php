<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('pollutions', function (Blueprint $table) {
        $table->id();
        $table->date('date');
        $table->string('numero');
        $table->string('zone');
        $table->string('coordonnees');
        $table->string('type_pollution');
        $table->string('image_satellite')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pollutions');
    }
};
