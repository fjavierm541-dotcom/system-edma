<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupo_docentes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('grupo_id')
                ->constrained('grupos')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('docente_id')
                ->constrained('docentes')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            /*
             * Valores previstos:
             * principal, auxiliar, sustituto
             */
            $table->string('tipo_asignacion', 30)
                ->default('principal');

            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();

            $table->boolean('activo')->default(true);

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->unique(
                ['grupo_id', 'docente_id', 'fecha_inicio'],
                'uq_grupo_docente_inicio'
            );

            $table->index(
                ['grupo_id', 'activo'],
                'idx_grupo_docentes_activos'
            );

            $table->index(
                ['docente_id', 'activo'],
                'idx_docente_grupos_activos'
            );

            $table->index(
                ['grupo_id', 'tipo_asignacion', 'activo'],
                'idx_grupo_tipo_asignacion'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupo_docentes');
    }
};