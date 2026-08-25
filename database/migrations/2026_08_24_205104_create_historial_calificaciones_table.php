<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_calificaciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('calificacion_final_id')
                ->constrained('calificaciones_finales')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->decimal('nota_anterior', 5, 2)->nullable();
            $table->decimal('nota_nueva', 5, 2)->nullable();

            $table->string('resultado_anterior', 30)->nullable();
            $table->string('resultado_nuevo', 30)->nullable();

            $table->text('observacion_anterior')->nullable();
            $table->text('observacion_nueva')->nullable();

            $table->text('motivo');

            $table->foreignId('cambiado_por')
                ->constrained('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->timestamp('cambiado_at');

            $table->timestamps();

            $table->index(
                ['calificacion_final_id', 'cambiado_at'],
                'idx_historial_calificacion_fecha'
            );

            $table->index('cambiado_por');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_calificaciones');
    }
};