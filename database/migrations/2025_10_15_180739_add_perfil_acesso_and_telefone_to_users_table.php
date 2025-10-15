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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('perfil_acesso', ['ADMIN', 'ATENDENTE', 'TECNICO'])
            ->nullable()
            ->after('email')
            ->comment('Perfil de acesso do usuário na aplicação');

            $table->string('telefone', 15)
            ->nullable()
            ->after('perfil_acesso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('perfil_acesso');
            $table->dropColumn('telefone');
        });
    }
};
