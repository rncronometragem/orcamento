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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 200);
            $table->string('categoria_nome', 50)->nullable();
            $table->string('tipo_doc', 10)->nullable(); // CPF ou CNPJ
            $table->string('documento', 30)->nullable();
            $table->text('obs')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('usuario_log', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
