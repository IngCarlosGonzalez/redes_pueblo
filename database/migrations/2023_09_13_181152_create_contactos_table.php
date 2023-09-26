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
        Schema::create('contactos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('owner_id')->default(0);
            $table->unsignedTinyInteger('nivel_en_red')->default(5);
            $table->string('clave_tipo', 20)->default('Integrante');
            $table->boolean('con_req_admin')->default(0);
            $table->string('requerimiento', 20)->nullable();
            $table->boolean('con_req_listo')->default(0);
            $table->boolean('tiene_usuario')->default(0);
            $table->unsignedBigInteger('user_asignado')->default(0);
            $table->boolean('user_vigente')->default(0);

            $table->string('clave_origen', 20)->default('OTROS');
            $table->foreignId('categoria_id')->constrained('categorias')->default(1);

            $table->string('nombre_completo', 60)->nullable();
            $table->string('clave_genero', 20)->default('SIN DATOS');
            $table->date('fecha_nacimiento')->nullable();
            $table->string('dato_de_curp', 20)->nullable();
            $table->string('foto_personal', 255)->nullable();
            $table->string('nombre_de_foto', 255)->nullable();

            $table->foreignId('municipio_id')->constrained('municipios')->default(39);
            $table->unsignedBigInteger('colonia_id')->default(0);
            $table->boolean('colonia_catalogada')->default(1);
            $table->string('domicilio_colonia', 60)->nullable();
            $table->string('domicilio_completo', 80)->nullable();
            $table->string('domicilio_codpost', 10)->nullable();

            $table->boolean('tiene_celular')->default(0);
            $table->string('telefono_movil', 20)->nullable();
            $table->boolean('tiene_watsapp')->default(0);
            $table->string('telefono_familiar', 20)->nullable();
            $table->string('telefono_recados', 20)->nullable();

            $table->boolean('tiene_correo')->default(0);
            $table->string('cuenta_de_correo', 80)->nullable();

            $table->boolean('tiene_facebook')->default(0);
            $table->string('contacto_facebook', 60)->nullable();
            $table->boolean('tiene_instagram')->default(0);
            $table->string('contacto_instagram', 60)->nullable();
            $table->boolean('tiene_telegram')->default(0);
            $table->string('contacto_telegram', 60)->nullable();
            $table->boolean('tiene_twitter')->default(0);
            $table->string('contacto_twitter', 60)->nullable();
            $table->boolean('tiene_otra_red')->default(0);
            $table->string('contacto_otra_red', 60)->nullable();

            $table->boolean('tiene_fotos_ine')->default(0);
            $table->string('foto_ine_de_frente', 255)->nullable();
            $table->string('nombre_foto_frente', 255)->nullable();
            $table->string('foto_ine_de_atras', 255)->nullable();
            $table->string('nombre_foto_atras', 255)->nullable();
            
            $table->boolean('con_domi_actual')->default(0);
            $table->string('domicilio_credencial', 80)->nullable();
            $table->string('numero_cred_ine', 20)->nullable();
            $table->string('clave_elector', 20)->nullable();
            $table->string('numero_ocr_ine', 20)->nullable();
            $table->date('vigencia_cred_ine')->nullable();
            $table->integer('distrito_federal')->nullable()->default(0);
            $table->integer('distrito_estatal')->nullable()->default(0);
            $table->integer('numero_de_ruta')->nullable()->default(0);
            $table->integer('numero_seccion')->nullable()->default(0);
            $table->boolean('seccion_prioritaria')->default(0);
            $table->boolean('datos_verificados')->default(0);

            $table->boolean('es_militante')->default(0);
            $table->string('numero_afiliacion', 20)->nullable();
            $table->date('fecha_afiliacion')->nullable();
            $table->string('numero_credencial', 20)->nullable();

            $table->boolean('en_comite')->default(0);
            $table->string('comite_base', 30)->nullable();
            $table->string('comite_rol', 30)->nullable();
            $table->string('defensor_voto', 30)->nullable();

            $table->boolean('en_partido')->default(0);
            $table->string('partido_area', 30)->nullable();
            $table->string('partido_puesto', 30)->nullable();
            $table->string('partido_lugar', 30)->nullable();

            $table->boolean('es_funcionario')->default(0);
            $table->string('puesto_cargo', 30)->nullable();
            $table->string('dependencia', 30)->nullable();
            $table->string('ubicacion', 30)->nullable();

            $table->boolean('interesa_afiliacion')->default(0);
            $table->boolean('interesa_defensavoto')->default(0);
            $table->boolean('interesa_armarcomite')->default(0);
            $table->boolean('interesa_unirsecomite')->default(0);
            $table->boolean('interesa_recibwatsaps')->default(0);
            $table->boolean('interesa_recibllamadas')->default(0);
            $table->boolean('interesa_recibcorreos')->default(0);
            $table->boolean('interesa_recibvisitas')->default(0);
            $table->boolean('interesa_capacitacion')->default(0);
            $table->boolean('interesa_asistireventos')->default(0);
            $table->boolean('interesa_asistirviajees')->default(0);
            $table->boolean('interesa_darasesorias')->default(0);

            $table->text('sus_aportaciones')->nullable();
            $table->text('mis_comentarios')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contactos');
    }
};
