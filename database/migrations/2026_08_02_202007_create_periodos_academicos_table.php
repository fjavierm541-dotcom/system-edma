<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periodos_academicos', function (Blueprint $table) {
            $table->id();

            $table->string('codigo', 30)->unique();
            $table->string('nombre', 100);

            $table->date('fecha_inicio');
            $table->date('fecha_fin');

            $table->date('fecha_inicio_matricula')->nullable();
            $table->date('fecha_fin_matricula')->nullable();

            $table->string('estado', 20)->default('planificado');

            $table->text('observaciones')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(
                ['fecha_inicio', 'fecha_fin'],
                'idx_periodos_fechas'
            );

            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periodos_academicos');
    }
};