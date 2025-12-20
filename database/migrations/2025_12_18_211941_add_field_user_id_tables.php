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
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
        });

        Schema::table('empresas', function (Blueprint $table) {
           $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
        });

        Schema::table('clientes', function (Blueprint $table) {
           $table->foreignId('user_cadastro_id')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->dropForeign('orcamentos_user_id_foreign');
            $table->dropColumn('user_id');
        });

        Schema::table('empresas', function (Blueprint $table) {
            $table->dropForeign('empresas_user_id_foreign');
            $table->dropColumn('user_id');
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign('clientes_user_cadastro_id_foreign');
            $table->dropColumn('user_cadastro_id');
        });
    }
};
