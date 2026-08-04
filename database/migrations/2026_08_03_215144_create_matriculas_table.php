<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();

            $table->string('codigo_matricula', 30)->unique();

            $table->foreignId('estudiante_id')
                ->constrained('estudiantes')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('grupo_id')
                ->constrained('grupos')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            /*
             * Se utiliza en la primera matrícula.
             * Las matrículas posteriores se solicitarán desde el portal.
             */
            $table->foreignId('solicitud_inscripcion_id')
                ->nullable()
                ->unique()
                ->constrained('solicitudes_inscripcion')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->date('fecha_matricula');

            /*
             * Precio histórico acordado para el nivel.
             * Actualmente: L 2,100.
             */
            $table->decimal('precio_nivel_acordado', 10, 2);

            /*
             * Cantidad acordada de cuotas:
             * 1, 2 o 3.
             */
            $table->unsignedTinyInteger('cantidad_cuotas');

            $table->decimal('monto_mora_acordado', 10, 2)
                ->default(100.00);

            /*
             * pendiente, activa, finalizada,
             * retirada, cancelada, rechazada
             */
            $table->string('estado', 30)->default('pendiente');

            $table->timestamp('aprobada_at')->nullable();
            $table->foreignId('aprobada_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->date('fecha_retiro')->nullable();
            $table->text('justificacion_retiro')->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(
                ['estudiante_id', 'estado'],
                'idx_matriculas_estudiante_estado'
            );

            $table->index(
                ['grupo_id', 'estado'],
                'idx_matriculas_grupo_estado'
            );

            $table->index('fecha_matricula');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};