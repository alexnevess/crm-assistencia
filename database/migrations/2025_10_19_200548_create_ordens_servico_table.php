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
        Schema::create('ordens_servico', function (Blueprint $table) {
            $table->id();
             $table->foreignId('empresa_id')
                  ->constrained() 
                  ->onDelete('cascade');

            $table->foreignId('cliente_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('equipamento_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('criador_user_id')
                  ->constrained('users')
                  ->onDelete('set null');

            $table->foreignId('atualizado_por_user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->enum('status', [
                'ABERTA',
                'EM_ESPERA',
                'AGUARDANDO_PEÇAS',
                'EM_DIAGNOSTICO',
                'EM_REPARO',
                'PRONTA',
                'CANCELADA'
            ])->default('ABERTA');

            $table->string('numero', 30);
            $table->unique(['empresa_id', 'numero']);

            $table->text('problema_relato')->nullable(); 
            $table->text('diagnostico')->nullable();
            $table->text('resolucao')->nullable();

            $table->timestamp('prazo_previsto')->nullable(); 
            $table->timestamp('finalizado_em')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordens_servico');
    }
};
