<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuentas_bancarias', function (Blueprint $table) {
            $table->id();

            $table->foreignId('empleado_id')
                ->constrained('empleados')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('institucion_financiera_id')
                ->constrained('instituciones_financieras')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('numero_cuenta', 50);

            $table->string('tipo_cuenta', 20);

            $table->string('moneda', 10)->default('HNL');

            $table->string('nombre_titular', 180)->nullable();

            $table->boolean('es_principal')->default(false);
            $table->boolean('activo')->default(true);

            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(
                ['institucion_financiera_id', 'numero_cuenta'],
                'uq_institucion_numero_cuenta'
            );

            $table->index(
                ['empleado_id', 'activo'],
                'idx_cuentas_empleado_activo'
            );

            $table->index(
                ['empleado_id', 'es_principal'],
                'idx_cuentas_empleado_principal'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuentas_bancarias');
    }
};