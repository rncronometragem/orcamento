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
        Schema::create('ordens', function (Blueprint $table) {
            $table->id();
            // Relacionamento com clientes
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');

            $table->string('cliente_nome', 200)->nullable();
            $table->string('status', 50)->default('pendente');
            $table->string('usuario_log', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordens');
    }
};
