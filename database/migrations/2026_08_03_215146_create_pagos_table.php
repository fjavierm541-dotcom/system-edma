<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            $table->string('codigo_pago', 30)->unique();

            $table->foreignId('matricula_id')
                ->constrained('matriculas')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->decimal('monto_total', 10, 2);

            /*
             * transferencia, deposito,
             * efectivo, tigo_money, otro
             */
            $table->string('metodo_pago', 30);

            $table->dateTime('fecha_pago');

            $table->string('numero_referencia', 100)->nullable();

            /*
             * pendiente_revision, aprobado,
             * rechazado, anulado
             */
            $table->string('estado', 30)
                ->default('pendiente_revision');

            $table->timestamp('revisado_at')->nullable();

            $table->foreignId('revisado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->text('motivo_rechazo')->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(
                ['matricula_id', 'estado'],
                'idx_pagos_matricula_estado'
            );

            $table->index(
                ['estado', 'fecha_pago'],
                'idx_pagos_estado_fecha'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};