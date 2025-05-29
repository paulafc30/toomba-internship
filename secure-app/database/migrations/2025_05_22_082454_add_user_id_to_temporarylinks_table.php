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
        Schema::table('temporarylinks', function (Blueprint $table) {
            // Verifica si la columna user_id ya existe para evitar errores si la migración se ejecuta varias veces
            if (!Schema::hasColumn('temporarylinks', 'user_id')) {
                // Añade la columna user_id como un unsignedBigInteger que puede ser nulo
                $table->unsignedBigInteger('user_id')->nullable()->after('id');

                // Añade la clave foránea para user_id referenciando la tabla users
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('set null'); // O 'cascade' si deseas que el enlace se borre con el usuario
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temporarylinks', function (Blueprint $table) {
            if (Schema::hasColumn('temporarylinks', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};