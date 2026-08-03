<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paises', function (Blueprint $table) {
            $table->id();

            $table->char('codigo_iso2', 2)->unique();
            $table->char('codigo_iso3', 3)->nullable()->unique();
            $table->string('nombre', 100)->unique();
            $table->string('nacionalidad', 100)->nullable();

            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paises');
    }
};