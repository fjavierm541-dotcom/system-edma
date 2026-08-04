<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aceptaciones_politicas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('persona_id')
                ->constrained('personas')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('solicitud_inscripcion_id')
                ->nullable()
                ->constrained('solicitudes_inscripcion')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            /*
             * Ejemplos:
             * terminos_servicio
             * politica_privacidad
             * precios
             */
            $table->string('tipo_politica', 50);

            $table->string('version', 30);

            $table->boolean('aceptado')->default(true);

            $table->timestamp('aceptado_at');

            $table->string('direccion_ip', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            $table->unique(
                [
                    'persona_id',
                    'solicitud_inscripcion_id',
                    'tipo_politica',
                    'version'
                ],
                'uq_aceptacion_persona_solicitud_politica'
            );

            $table->index(
                ['solicitud_inscripcion_id', 'tipo_politica'],
                'idx_aceptaciones_solicitud_tipo'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aceptaciones_politicas');
    }
};