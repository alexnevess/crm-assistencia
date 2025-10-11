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
        Schema::table('empresas', function (Blueprint $table) {
            //adciona 'user_id' a tabela empresas
            $table->foreignId('user_id')->constrained()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            //Deleta a chave estrangeira antes de deletar a coluna
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
