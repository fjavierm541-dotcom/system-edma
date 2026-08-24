<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_cambios_grupo_matricula', function (Blueprint $table) {
            $table->id();

            $table->foreignId('matricula_id')
                ->constrained('matriculas')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('grupo_anterior_id')
                ->constrained('grupos')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('grupo_nuevo_id')
                ->constrained('grupos')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->text('motivo')->nullable();

            $table->foreignId('cambiado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->timestamp('cambiado_at');

            $table->timestamps();

            $table->index(
                ['matricula_id', 'cambiado_at'],
                'idx_historial_cambio_grupo_matricula_fecha'
            );

            $table->index(
                ['grupo_anterior_id', 'grupo_nuevo_id'],
                'idx_historial_cambio_grupos'
            );

            $table->index('cambiado_por');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_cambios_grupo_matricula');
    }
};