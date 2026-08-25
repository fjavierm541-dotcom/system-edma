<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calificaciones_finales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('matricula_id')
                ->unique()
                ->constrained('matriculas')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->decimal('nota_final', 5, 2)->nullable();

            /*
             * aprobado
             * reprobado
             * incompleto
             * retirado
             */
            $table->string('resultado', 30)->nullable();

            $table->text('observacion_docente')->nullable();

            /*
             * borrador
             * confirmada
             * bloqueada
             */
            $table->string('estado', 20)
                ->default('borrador');

            $table->foreignId('registrada_por')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->timestamp('registrada_at')->nullable();
            $table->timestamp('confirmada_at')->nullable();
            $table->timestamp('bloqueada_at')->nullable();

            $table->timestamps();

            $table->index(
                ['estado', 'confirmada_at'],
                'idx_calificaciones_estado_confirmada'
            );

            $table->index(
                ['resultado', 'estado'],
                'idx_calificaciones_resultado_estado'
            );

            $table->index('registrada_por');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calificaciones_finales');
    }
};