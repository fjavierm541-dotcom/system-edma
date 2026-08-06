<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_inscripcion', function (Blueprint $table) {
            $table->id();

            $table->string('codigo_solicitud', 30)->unique();

            // Persona interesada en ingresar
            $table->foreignId('persona_id')
                ->constrained('personas')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            // Grupo y horario seleccionados por el solicitante
            $table->foreignId('grupo_solicitado_id')
                ->constrained('grupos')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

                $table->foreignId('grupo_autorizado_id')
                ->nullable()
                ->constrained('grupos')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            // Cómo conoció la academia
            $table->foreignId('fuente_referencia_id')
                ->nullable()
                ->constrained('fuentes_referencia')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('fuente_referencia_otro', 150)
                ->nullable();

            /*
             * Clasificación seleccionada en el formulario:
             * infantil, joven_adulto
             */
            $table->string('segmento_solicitado', 30);

            /*
             * Si solicita comenzar por encima de A0,
             * deberá realizar examen de ubicación.
             */
            $table->boolean('requiere_examen_ubicacion')
                ->default(false);

            $table->foreignId('nivel_solicitado_id')
                ->nullable()
                ->constrained('niveles')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            /*
             * Nivel finalmente autorizado por administración
             * después de revisar la solicitud o el examen.
             */
            $table->foreignId('nivel_autorizado_id')
                ->nullable()
                ->constrained('niveles')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            /*
             * Estados previstos:
             * borrador
             * pendiente
             * en_revision
             * pendiente_examen
             * aprobada
             * rechazada
             * cancelada
             */
            $table->string('estado', 30)
                ->default('pendiente');

            $table->timestamp('enviada_at')->nullable();
            $table->timestamp('revisada_at')->nullable();
            $table->timestamp('resuelta_at')->nullable();

            $table->foreignId('revisada_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->text('observaciones_solicitante')->nullable();
            $table->text('observaciones_administracion')->nullable();

            $table->text('motivo_rechazo')->nullable();

            $table->boolean('recomienda_otro_estudiante')
                ->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index(
                ['persona_id', 'estado'],
                'idx_solicitudes_persona_estado'
            );

            $table->index(
                ['grupo_solicitado_id', 'estado'],
                'idx_solicitudes_grupo_estado'
            );

            $table->index(
                ['estado', 'enviada_at'],
                'idx_solicitudes_estado_envio'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_inscripcion');
    }
};