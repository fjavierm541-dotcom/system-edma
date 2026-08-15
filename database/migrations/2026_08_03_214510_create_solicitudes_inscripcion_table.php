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

            $table->foreignId('persona_id')
                ->constrained('personas')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('fuente_referencia_id')
                ->nullable()
                ->constrained('fuentes_referencia')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('fuente_referencia_otro', 150)->nullable();

            $table->string('segmento_solicitado', 30);

            // Nivel que el aspirante considera que podría cursar.
            $table->foreignId('nivel_solicitado_id')
                ->nullable()
                ->constrained('niveles')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            // Nivel reconocido al momento de resolver la solicitud.
            // Para todo nuevo estudiante aprobado será A0.
            $table->foreignId('nivel_autorizado_id')
                ->nullable()
                ->constrained('niveles')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->boolean('requiere_examen_ubicacion')
                ->default(false);

            /*
             * borrador
             * pendiente
             * en_revision
             * aprobada
             * rechazada
             * cancelada
             */
            $table->string('estado', 30)->default('pendiente');

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
                ['nivel_solicitado_id', 'estado'],
                'idx_solicitudes_nivel_estado'
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