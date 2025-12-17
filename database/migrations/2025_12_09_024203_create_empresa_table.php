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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 200)->nullable();
            $table->string('cnpj', 100)->nullable();
            $table->string('insc_estadual', 200)->nullable();

            $table->string('cep', 100)->nullable();
            $table->string('rua', 100)->nullable();
            $table->string('numero', 200)->nullable();
            $table->string('complemento', 150)->nullable();
            $table->string('cidade', 25)->nullable();
            $table->string('bairro', 25)->nullable();
            $table->string('uf', 25)->nullable();

            $table->string('email', 250)->nullable();
            $table->string('telefone', 200)->nullable();
            $table->string('celular', 100)->nullable();
            $table->string('site', 100)->nullable();

            $table->string('logo', 100)->nullable();
            $table->string('cor_barra', 20)->default('#0275d8');
            $table->string('cor_texto', 20)->default('#f7f7f7');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
