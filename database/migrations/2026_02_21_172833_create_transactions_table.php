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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Usuário que criou
            $table->decimal('value', 10, 2); // Valor em Reais (Ex: 1000.50)
            $table->string('cpf', 11); // CPF do portador
            $table->string('document_path')->nullable(); // Caminho do PDF/Imagem
            $table->enum('status', ['Em processamento', 'Aprovada', 'Negada'])->default('Em processamento');
            $table->timestamps(); // Cria o 'created_at' e 'updated_at' automaticamente
            $table->softDeletes(); // Necessário para o "Excluir" com Soft Delete solicitado
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
