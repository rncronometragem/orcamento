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
            $table->timestamp('data_evento')->nullable()->after('pode_ver_unitarios');
            $table->string('local_evento')->nullable()->after('data_evento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::table('orcamentos', function (Blueprint $table) {
            $table->dropColumn('data_evento');
            $table->dropColumn('local_evento');
        });
    }
};
