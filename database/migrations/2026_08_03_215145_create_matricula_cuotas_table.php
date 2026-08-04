<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matricula_cuotas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('matricula_id')
                ->constrained('matriculas')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->unsignedTinyInteger('numero_cuota');

            $table->string('concepto', 120);

            $table->decimal('monto', 10, 2);
            $table->date('fecha_vencimiento');

            $table->decimal('mora_aplicada', 10, 2)
                ->default(0.00);

            $table->boolean('mora_justificada')
                ->default(false);

            /*
             * pendiente, parcial, pagada,
             * vencida, anulada
             */
            $table->string('estado', 20)->default('pendiente');

            $table->date('fecha_pago_completo')->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->unique(
                ['matricula_id', 'numero_cuota'],
                'uq_matricula_numero_cuota'
            );

            $table->index(
                ['estado', 'fecha_vencimiento'],
                'idx_cuotas_estado_vencimiento'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matricula_cuotas');
    }
};