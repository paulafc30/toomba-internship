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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->string('permission_type')->nullable();
            $table->foreignId('folder_id')->constrained('folders')->onDelete('restrict');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps(); // Manteniendo las timestamps si las necesitas
            $table->primary(['id', 'folder_id', 'user_id']);
            $table->index(['user_id']);
            $table->index(['folder_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};