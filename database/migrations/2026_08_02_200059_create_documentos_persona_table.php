<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos_persona', function (Blueprint $table) {
            $table->id();

            $table->foreignId('persona_id')
                ->constrained('personas')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('tipo_documento', 50);

            $table->string('nombre_original', 255);
            $table->string('nombre_almacenado', 255);
            $table->string('ruta_archivo', 500);

            $table->string('extension', 20)->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('tamano_bytes')->nullable();

            $table->date('fecha_emision')->nullable();
            $table->date('fecha_vencimiento')->nullable();

            $table->boolean('verificado')->default(false);
            $table->timestamp('verificado_at')->nullable();

            $table->foreignId('verificado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->string('estado', 20)->default('activo');
            $table->text('observaciones')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(
                ['persona_id', 'tipo_documento'],
                'idx_documentos_persona_tipo'
            );

            $table->index('estado');
            $table->index('verificado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_persona');
    }
};