<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //    'name' => 'Test User',
        //    'email' => 'test@example.com',
        //]);

        // $this->call([
        //   TransactionSeeder::class,
        // ]);

        // 1. Criar o usuário para o avaliador
        $user = User::create([
            'name' => 'Avaliador Teste',
            'email' => 'teste@sistema.com',
            'password' => Hash::make('senha123'),
        ]);

        // 2. Criar 3 transações de exemplo para este usuário
        Transaction::create([
            'user_id' => $user->id,
            'value' => 250.50,
            'cpf' => '12345678910',
            'status' => 'Aprovada',
            'document_path' => 'comprovantes/exemplo1.pdf',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'value' => 1500.00,
            'cpf' => '98765432100',
            'status' => 'Em processamento',
            'document_path' => 'comprovantes/exemplo2.png',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'value' => 89.90,
            'cpf' => '55544433322',
            'status' => 'Negada',
            'document_path' => 'comprovantes/exemplo3.jpg',
        ]);
    }
}
