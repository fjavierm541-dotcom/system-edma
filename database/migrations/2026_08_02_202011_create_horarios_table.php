<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();

            $table->string('nombre', 100)->nullable();

            $table->time('hora_inicio');
            $table->time('hora_fin');

            $table->string('zona_horaria', 50)
                ->default('America/Tegucigalpa');

            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->unique(
                ['hora_inicio', 'hora_fin', 'zona_horaria'],
                'uq_horarios_rango_zona'
            );

            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};