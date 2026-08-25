<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('periodos_academicos', function (Blueprint $table) {
            $table->dateTime('calificaciones_desde')
                ->nullable()
                ->after('fecha_fin_matricula');

            $table->dateTime('calificaciones_hasta')
                ->nullable()
                ->after('calificaciones_desde');

            $table->index(
                ['calificaciones_desde', 'calificaciones_hasta'],
                'idx_periodos_ventana_calificaciones'
            );
        });
    }

    public function down(): void
    {
        Schema::table('periodos_academicos', function (Blueprint $table) {
            $table->dropIndex('idx_periodos_ventana_calificaciones');

            $table->dropColumn([
                'calificaciones_desde',
                'calificaciones_hasta',
            ]);
        });
    }
};