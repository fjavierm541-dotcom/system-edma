<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('docentes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('empleado_id')
                ->unique()
                ->constrained('empleados')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('codigo_docente', 30)->unique();

            $table->string('especialidad', 150)->nullable();

            $table->date('fecha_inicio_docencia')->nullable();

            $table->string('estado', 20)->default('activo');

            $table->text('observaciones')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('estado');
            $table->index('especialidad');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docentes');
    }
};