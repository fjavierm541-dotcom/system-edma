<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programas', function (Blueprint $table) {
            $table->id();

            $table->string('codigo', 20)->unique();
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();

            // Ejemplos: infantil, joven_adulto
            $table->string('segmento', 30)->nullable();

            $table->string('estado', 20)->default('activo');

            $table->timestamps();
            $table->softDeletes();

            $table->index('nombre');
            $table->index('segmento');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programas');
    }
};