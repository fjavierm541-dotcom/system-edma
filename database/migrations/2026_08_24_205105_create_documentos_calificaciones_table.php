<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos_calificaciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('grupo_id')
                ->constrained('grupos')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('periodo_academico_id')
                ->constrained('periodos_academicos')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('subido_por')
                ->constrained('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('nombre_original', 255);
            $table->string('nombre_almacenado', 255);
            $table->string('ruta_archivo', 500);

            $table->string('extension', 20)->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('tamano_bytes')->nullable();

            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->index(
                ['grupo_id', 'periodo_academico_id'],
                'idx_documentos_calificaciones_grupo_periodo'
            );

            $table->index(
                ['subido_por', 'created_at'],
                'idx_documentos_calificaciones_usuario_fecha'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_calificaciones');
    }
};