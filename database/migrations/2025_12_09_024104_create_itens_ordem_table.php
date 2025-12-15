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
        Schema::create('itens_ordem', function (Blueprint $table) {
            $table->id();
            // Relacionamento com ordens
            $table->foreignId('ordem_id')->constrained('ordens')->onDelete('cascade');

            $table->string('descricao', 200)->nullable();
            $table->integer('quantidade')->default(1);
            $table->decimal('preco', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itens_ordem');
    }
};
