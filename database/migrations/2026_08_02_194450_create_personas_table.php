<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();

            // Nombres
            $table->string('primer_nombre', 50);
            $table->string('segundo_nombre', 50)->nullable();
            $table->string('primer_apellido', 50);
            $table->string('segundo_apellido', 50)->nullable();

            // Identificación
            $table->string('tipo_documento', 30)->nullable();
            $table->string('numero_documento', 50)->nullable();
            $table->string('rtn', 30)->nullable();

            // Datos personales
            $table->date('fecha_nacimiento')->nullable();
            $table->string('sexo', 20)->nullable();
            $table->string('estado_civil', 30)->nullable();
            $table->string('nacionalidad', 100)->nullable();

            // Contacto
            $table->string('correo_personal', 150)->nullable();
            $table->string('telefono_movil', 30)->nullable();
            $table->string('telefono_fijo', 30)->nullable();
            $table->boolean('telefono_movil_whatsapp')->default(true);

            // Fotografía
            $table->string('foto_perfil', 500)->nullable();

            // Residencia
            $table->foreignId('pais_residencia_id')
                ->nullable()
                ->constrained('paises')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->text('direccion')->nullable();
            $table->string('ciudad_municipio', 120)->nullable();
            $table->string('departamento_estado', 120)->nullable();

            // Control
            $table->string('estado', 20)->default('activo');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(
                ['tipo_documento', 'numero_documento'],
                'uq_personas_tipo_numero_documento'
            );

            $table->unique('rtn', 'uq_personas_rtn');

            $table->index(
                ['primer_apellido', 'primer_nombre'],
                'idx_personas_apellido_nombre'
            );

            $table->index('correo_personal');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};