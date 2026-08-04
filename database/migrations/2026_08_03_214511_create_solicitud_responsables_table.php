<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitud_responsables', function (Blueprint $table) {
            $table->id();

            $table->foreignId('solicitud_inscripcion_id')
                ->constrained('solicitudes_inscripcion')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('responsable_persona_id')
                ->constrained('personas')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('parentesco', 50)->nullable();

            $table->boolean('es_principal')->default(true);
            $table->boolean('recibe_notificaciones')->default(true);
            $table->boolean('autoriza_inscripcion')->default(true);

            $table->timestamps();

            $table->unique(
                [
                    'solicitud_inscripcion_id',
                    'responsable_persona_id'
                ],
                'uq_solicitud_responsable'
            );

            $table->index(
                [
                    'solicitud_inscripcion_id',
                    'es_principal'
                ],
                'idx_solicitud_responsable_principal'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitud_responsables');
    }
};