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
        Schema::create('seccions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seccion_id')->unique();
            $table->foreignId('municipio_id')->constrained('municipios')->default(39);
            $table->integer('numero_ruta')->nullable()->default(0);
            $table->integer('distrito_fed')->nullable()->default(0);
            $table->integer('distrito_loc')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seccions');
    }
};
