<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('niveles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('programa_id')
                ->constrained('programas')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('codigo', 20);
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();

            // Determina la secuencia académica dentro del programa.
            $table->unsignedTinyInteger('orden');

            $table->unsignedSmallInteger('duracion_semanas')
                ->default(12);

            $table->decimal('nota_minima_aprobacion', 5, 2)
                ->default(70.00);

            $table->string('estado', 20)->default('activo');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(
                ['programa_id', 'codigo'],
                'uq_niveles_programa_codigo'
            );

            $table->unique(
                ['programa_id', 'orden'],
                'uq_niveles_programa_orden'
            );

            $table->index(
                ['programa_id', 'estado'],
                'idx_niveles_programa_estado'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('niveles');
    }
};