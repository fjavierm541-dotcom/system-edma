<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estudiante_responsables', function (Blueprint $table) {
            $table->id();

            $table->foreignId('estudiante_id')
                ->constrained('estudiantes')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('responsable_persona_id')
                ->constrained('personas')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('parentesco', 50)->nullable();

            $table->boolean('es_principal')->default(false);
            $table->boolean('recibe_notificaciones')->default(true);
            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->unique(
                ['estudiante_id', 'responsable_persona_id'],
                'uq_estudiante_responsable'
            );

            $table->index(
                ['estudiante_id', 'es_principal'],
                'idx_estudiante_responsable_principal'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudiante_responsables');
    }
};