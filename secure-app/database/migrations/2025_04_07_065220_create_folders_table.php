<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->id(); // Crea una columna 'id' como clave primaria autoincremental (BIGINT UNSIGNED)
            $table->string('name'); // Crea una columna 'name' de tipo VARCHAR con una longitud máxima de 255 caracteres
            $table->timestamps(); // Crea automáticamente las columnas 'created_at' y 'updated_at' de tipo TIMESTAMP
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folders'); // Elimina la tabla 'folders' cuando se hace rollback de la migración
    }
};