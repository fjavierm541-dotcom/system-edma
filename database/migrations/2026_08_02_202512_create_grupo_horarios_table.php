<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupo_horarios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('grupo_id')
                ->constrained('grupos')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('horario_id')
                ->constrained('horarios')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            /*
             * Valores previstos:
             * lunes, martes, miercoles, jueves,
             * viernes, sabado, domingo
             */
            $table->string('dia_semana', 15);

            $table->timestamps();

            $table->unique(
                ['grupo_id', 'dia_semana', 'horario_id'],
                'uq_grupo_dia_horario'
            );

            $table->index(
                ['grupo_id', 'dia_semana'],
                'idx_grupo_horarios_dia'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupo_horarios');
    }
};