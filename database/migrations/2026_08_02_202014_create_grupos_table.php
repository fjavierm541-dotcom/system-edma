<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('nivel_id')
                ->constrained('niveles')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('periodo_academico_id')
                ->constrained('periodos_academicos')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('codigo', 40)->unique();
            $table->string('nombre', 100);

            $table->string('modalidad', 30)->default('virtual');

            $table->unsignedTinyInteger('cupo_minimo')->default(4);
            $table->unsignedTinyInteger('cupo_maximo')->default(20);

            $table->date('fecha_inicio');
            $table->date('fecha_fin');

            $table->string('estado', 30)->default('planificado');

            $table->text('observaciones')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(
                ['nivel_id', 'periodo_academico_id'],
                'idx_grupos_nivel_periodo'
            );

            $table->index(
                ['estado', 'fecha_inicio'],
                'idx_grupos_estado_inicio'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};