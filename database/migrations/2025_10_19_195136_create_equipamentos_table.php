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
        Schema::create('equipamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')
                  ->constrained() 
                  ->onDelete('cascade');

            $table->foreignId('cliente_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('equip_marca', 80)->nullable();
            $table->string('equip_modelo', 80);
            $table->string('equip_numero_serie', 120)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipamentos');
    }
};
