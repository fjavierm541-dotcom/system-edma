<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comprobantes_pago', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pago_id')
                ->constrained('pagos')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('nombre_original', 255);
            $table->string('nombre_almacenado', 255);
            $table->string('ruta_archivo', 500);

            $table->string('extension', 20)->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('tamano_bytes')->nullable();

            $table->timestamps();

            $table->index('pago_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comprobantes_pago');
    }
};