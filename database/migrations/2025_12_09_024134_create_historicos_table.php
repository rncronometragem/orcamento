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
        Schema::create('historicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');

            $table->string('cliente_nome', 200)->nullable();
            $table->text('descricao')->nullable();
            $table->string('usuario_log', 100)->nullable();

            // Arquivos
            $table->string('arquivo_titulo', 100)->nullable();
            $table->string('arquivo_nome', 100)->nullable();
            $table->string('arquivo_caminho', 100)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historicos');
    }
};
