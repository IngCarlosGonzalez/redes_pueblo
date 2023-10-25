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
        Schema::table('contactos', function (Blueprint $table) {
            $table->string('palabras_clave')->nullable()->after('mis_comentarios');
            $table->Integer('nivel_de_owner')->nullable()->after('palabras_clave');
            $table->unsignedBigInteger('ascend_nivel_1')->nullable()->after('nivel_de_owner');
            $table->unsignedBigInteger('ascend_nivel_2')->nullable()->after('ascend_nivel_1');
            $table->unsignedBigInteger('ascend_nivel_3')->nullable()->after('ascend_nivel_2');
            $table->unsignedBigInteger('ascend_nivel_4')->nullable()->after('ascend_nivel_3');
            $table->string('path_en_la_red', 60)->nullable()->after('ascend_nivel_4');
            $table->string('orden_listados', 60)->nullable()->after('path_en_la_red');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contactos', function (Blueprint $table) {
            $table->dropcolumn('orden_listados');
            $table->dropcolumn('path_en_la_red');
            $table->dropcolumn('ascend_nivel_4');
            $table->dropcolumn('ascend_nivel_3');
            $table->dropcolumn('ascend_nivel_2');
            $table->dropcolumn('ascend_nivel_1');
            $table->dropcolumn('nivel_de_owner');
            $table->dropcolumn('palabras_clave');
        });
    }
};
