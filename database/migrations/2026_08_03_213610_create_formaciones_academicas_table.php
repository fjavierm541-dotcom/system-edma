<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formaciones_academicas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('persona_id')
                ->constrained('personas')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('nivel_academico', 50)->nullable();

            $table->string('titulo_obtenido', 200);

            $table->string('institucion_educativa', 200);

            $table->foreignId('pais_id')
                ->nullable()
                ->constrained('paises')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->year('anio_graduacion')->nullable();

            $table->foreignId('documento_persona_id')
                ->nullable()
                ->constrained('documentos_persona')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->boolean('es_principal')->default(false);

            $table->string('estado', 20)->default('activo');

            $table->text('observaciones')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(
                ['persona_id', 'estado'],
                'idx_formaciones_persona_estado'
            );

            $table->index('nivel_academico');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formaciones_academicas');
    }
};