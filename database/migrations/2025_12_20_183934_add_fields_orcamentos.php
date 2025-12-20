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
           $table->timestamp('data_resposta')->nullable();
           $table->timestamp('data_expiracao')->nullable();
           $table->boolean('pode_ver_unitarios')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->dropColumn('data_resposta');
            $table->dropColumn('data_expiracao');
            $table->dropColumn('pode_ver_unitarios');
        });
    }
};
