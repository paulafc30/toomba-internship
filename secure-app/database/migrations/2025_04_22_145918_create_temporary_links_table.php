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
        Schema::create('temporarylinks', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique(); 
            $table->string('name')->nullable(); 
            $table->string('email')->nullable(); 
            $table->timestamp('expires_at'); 
            $table->string('password')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporarylinks');
    }
};