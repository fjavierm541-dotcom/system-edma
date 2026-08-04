<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();

            $table->foreignId('persona_id')
                ->unique()
                ->constrained('personas')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('codigo_empleado', 30)->unique();

            $table->date('fecha_ingreso');
            $table->date('fecha_salida')->nullable();

            $table->unsignedSmallInteger('cantidad_hijos')
                ->default(0);

            $table->string('institucion_laboral_actual', 180)
                ->nullable();

            $table->string('horario_laboral_actual', 150)
                ->nullable();

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
        Schema::dropIfExists('empleados');
    }
};