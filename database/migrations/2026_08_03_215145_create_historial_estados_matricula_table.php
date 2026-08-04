<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_estados_matricula', function (Blueprint $table) {
            $table->id();

            $table->foreignId('matricula_id')
                ->constrained('matriculas')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('estado_anterior', 30)->nullable();
            $table->string('estado_nuevo', 30);

            $table->text('motivo')->nullable();

            $table->foreignId('cambiado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->timestamp('cambiado_at');

            $table->timestamps();

            $table->index(
                ['matricula_id', 'cambiado_at'],
                'idx_historial_matricula_fecha'
            );

            $table->index('estado_nuevo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_estados_matricula');
    }
};