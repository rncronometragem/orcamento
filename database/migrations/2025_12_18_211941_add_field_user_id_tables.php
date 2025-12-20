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
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
        });

        Schema::table('empresas', function (Blueprint $table) {
           $table->unsignedBigInteger('user_id')->nullable();
        });

        Schema::table('clientes', function (Blueprint $table) {
           $table->unsignedBigInteger('user_cadastro_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });

        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('user_cadastro_id');
        });
    }
};
