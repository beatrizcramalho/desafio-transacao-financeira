<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\User;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Garante que existe pelo menos um usuário para associar às transações
        $user = User::first();

        if ($user) {
            Transaction::create([
                'user_id' => $user->id,
                'value' => 1500.50,
                'cpf' => '12345678901',
                'status' => 'Aprovada',
                'created_at' => now(),
            ]);

            Transaction::create([
                'user_id' => $user->id,
                'value' => 89.90,
                'cpf' => '98765432100',
                'status' => 'Em processamento',
                'created_at' => now()->subDay(),
            ]);

            Transaction::create([
                'user_id' => $user->id,
                'value' => 500.00,
                'cpf' => '11122233344',
                'status' => 'Negada',
                'created_at' => now()->subDays(2),
            ]);
        }
    }
}