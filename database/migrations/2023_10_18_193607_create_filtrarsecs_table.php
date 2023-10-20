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
        Schema::create('filtrarsecs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ejecutor')->default(0);
            $table->unsignedBigInteger('seccion')->default(0);
            $table->unsignedBigInteger('municipio_id')->default(0);
            $table->string('nombre_mpio')->nullable();
            $table->string('descripcion')->nullable();
            $table->timestamps();
            $table->index('ejecutor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filtrarsecs');
    }
};
