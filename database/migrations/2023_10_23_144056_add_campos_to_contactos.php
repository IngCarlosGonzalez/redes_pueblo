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
            $table->string('palabras_clave', 99)->nullable()->after('mis_comentarios');
            $table->Integer('nivel_de_owner')->nullable()->after('palabras_clave');
            $table->Integer('ascend_nivel_1')->nullable()->after('nivel_de_owner');
            $table->Integer('ascend_nivel_2')->nullable()->after('ascend_nivel_1');
            $table->Integer('ascend_nivel_3')->nullable()->after('ascend_nivel_2');
            $table->Integer('ascend_nivel_4')->nullable()->after('ascend_nivel_3');
            $table->string('path_en_la_red', 99)->nullable()->after('ascend_nivel_4');
            $table->string('orden_listados', 99)->nullable()->after('path_en_la_red');
            $table->foreign('owner_id')->references('id')->on('users');
            $table->index('colonia_id', 'idx_x_colonia');
            $table->index('numero_seccion', 'idx_x_seccion');
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
            $table->dropForeign(['owner_id']);
            $table->dropIndex('idx_x_colonia');
            $table->dropIndex('idx_x_seccion');
        });
    }
};
