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
        Schema::create('colonias', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo')->unsigned()->default(0);
            $table->integer('entidad')->unsigned()->default(0);
            $table->foreignId('municipio_id')->constrained('municipios')->cascadeOnDelete();
            $table->string('nombre_mpio', 30)->nullable();
            $table->integer('distrito_fed')->unsigned()->default(0);
            $table->integer('distrito_local')->unsigned()->default(0);
            $table->integer('numero_de_ruta')->unsigned()->default(0);
            $table->integer('seccion')->unsigned()->default(0);
            $table->string('tipo_seccion', 20)->nullable();
            $table->string('tipo_colonia', 30)->nullable();
            $table->string('nombre_colonia', 60)->nullable();
            $table->integer('cod_post_colon')->unsigned()->default(0);
            $table->integer('num_control')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colonias');
    }
};
