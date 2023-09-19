<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contacto>
 */
class ContactoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id' => 1,

            'nivel_en_red'   => 5,
            'clave_tipo'     => 'Integrante',
            'con_req_admin'  => 0,
            'requerimiento'  => '',
            'con_req_listo'  => 0,
            'tiene_usuario'  => 0,
            'user_asignado'  => 0,
            'user_vigente'   => 0,

            'clave_origen'     => 'OTROS',
            'categoria_id'     => $this->faker->numberBetween($min = 1, $max = 6),

            'nombre_completo'  => $this->faker->name,
            'clave_genero'     => 'SIN DATOS',
            'fecha_nacimiento' => $this->faker->date($max = '2000-01-01'),

            'dato_de_curp'     => 'SIN DATOS',

            'municipio_id'        => 30,
            'colonia_id'          => 0,
            'colonia_catalogada'  => 0,
            'domicilio_colonia'   => 'SIN DATOS',
            'domicilio_completo'     => $this->faker->address,
            'domicilio_codpost'   => '25678',

            'tiene_celular'     => 1,
            'telefono_movil'    => $this->faker->tollFreePhoneNumber,
            'tiene_watsapp'     => $this->faker->numberBetween($min = 0, $max = 1),
            'telefono_familiar' => '',
            'telefono_recados'  => '',
            'tiene_correo'      => 1,
            'cuenta_de_correo'  => $this->faker->freeEmail,

            'tiene_facebook'      => 1,
            'contacto_facebook'   => $this->faker->word,
            'tiene_instagram'     => 0,
            'contacto_instagram'  => '',
            'tiene_telegram'      => 1,
            'contacto_telegram'   => $this->faker->word,
            'tiene_twitter'       => 0,
            'contacto_twitter'    => '',
            'tiene_otra_red'      => 1,
            'contacto_otra_red'   => $this->faker->word,

            'tiene_fotos_ine' => 0,
            'foto_ine_frente' => null,
            'foto_ine_detras' => null,
            
            'numero_cred_ine'     => $this->faker->word,
            'clave_elector'       => $this->faker->word,
            'numero_ocr_ine'      => $this->faker->word,
            'vigencia_cred_ine'   => $this->faker->date('2026-12-31'),
            'distrito_federal'    => 0,
            'distrito_estatal'    => 0,
            'numero_de_ruta'      => 0,
            'numero_seccion'      => 0,
            'seccion_prioritaria' => 0,

            'es_militante'      => 0,
            'numero_afiliacion' => '',
            'fecha_afiliacion'  => null,
            'numero_credencial' => '',
            'en_comite'         => 0,
            'comite_base'       => '',
            'comite_rol'        => '',
            'defensor_voto'     => '',
            'en_partido'        => 0,
            'partido_area'      => '',
            'partido_puesto'    => '',
            'partido_lugar'     => '',
            'es_funcionario'    => 0,
            'puesto_cargo'      => '',
            'dependencia'       => '',
            'ubicacion'         => '',

            'interesa_afiliacion'      => $this->faker->numberBetween($min = 0, $max = 1),
            'interesa_defensavoto'     => $this->faker->numberBetween($min = 0, $max = 1),
            'interesa_armarcomite'     => $this->faker->numberBetween($min = 0, $max = 1),
            'interesa_unirsecomite'    => $this->faker->numberBetween($min = 0, $max = 1),
            'interesa_recibwatsaps'    => $this->faker->numberBetween($min = 0, $max = 1),
            'interesa_recibllamadas'   => $this->faker->numberBetween($min = 0, $max = 1),
            'interesa_recibcorreos'    => $this->faker->numberBetween($min = 0, $max = 1),
            'interesa_recibvisitas'    => $this->faker->numberBetween($min = 0, $max = 1),
            'interesa_capacitacion'    => $this->faker->numberBetween($min = 0, $max = 1),
            'interesa_asistireventos'  => $this->faker->numberBetween($min = 0, $max = 1),
            'interesa_asistirviajees'  => $this->faker->numberBetween($min = 0, $max = 1),
            'interesa_darasesorias'    => $this->faker->numberBetween($min = 0, $max = 1),
            'sus_aportaciones'         => $this->faker->word(100),
            'mis_comentarios'          => $this->faker->word(100),

        ];
    }
}
