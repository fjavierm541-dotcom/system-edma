<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examenes_ubicacion', function (Blueprint $table) {
            $table->id();

            $table->foreignId('estudiante_id')
                ->constrained('estudiantes')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            // Solo como trazabilidad hacia la inscripción inicial.
            $table->foreignId('solicitud_inscripcion_id')
                ->nullable()
                ->constrained('solicitudes_inscripcion')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('nivel_solicitado_id')
                ->nullable()
                ->constrained('niveles')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('nivel_autorizado_id')
                ->nullable()
                ->constrained('niveles')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->unsignedTinyInteger('numero_intento')->default(1);

            $table->dateTime('fecha_programada')->nullable();
            $table->dateTime('fecha_realizacion')->nullable();

            $table->decimal('calificacion', 5, 2)->nullable();

            /*
             * pendiente
             * programado
             * realizado
             * ausente
             * cancelado
             */
            $table->string('estado', 30)->default('pendiente');

            $table->foreignId('evaluado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->text('resultado_observaciones')->nullable();

            $table->timestamps();

            $table->unique(
                ['estudiante_id', 'numero_intento'],
                'uq_examen_estudiante_intento'
            );

            $table->index(
                ['estudiante_id', 'estado'],
                'idx_examenes_estudiante_estado'
            );

            $table->index(
                ['estado', 'fecha_programada'],
                'idx_examenes_estado_fecha'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examenes_ubicacion');
    }
};