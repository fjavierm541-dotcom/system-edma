<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pago_cuotas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pago_id')
                ->constrained('pagos')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('matricula_cuota_id')
                ->constrained('matricula_cuotas')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->decimal('monto_aplicado', 10, 2);

            $table->timestamps();

            $table->unique(
                ['pago_id', 'matricula_cuota_id'],
                'uq_pago_cuota'
            );

            $table->index('matricula_cuota_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pago_cuotas');
    }
};