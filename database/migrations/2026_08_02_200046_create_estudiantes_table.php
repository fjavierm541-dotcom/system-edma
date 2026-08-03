<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('persona_id')
                ->unique()
                ->constrained('personas')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('nivel_escolaridad_id')
                ->nullable()
                ->constrained('niveles_escolaridad')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('codigo_estudiante', 20)->unique();

            $table->string('profesion_ocupacion', 150)->nullable();

            $table->date('fecha_ingreso')->nullable();

            $table->string('estado', 20)->default('activo');

            $table->text('observaciones')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('estado');
            $table->index('fecha_ingreso');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};