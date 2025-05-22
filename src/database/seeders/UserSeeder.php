<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        $plainTextToken = 'test'; // тестово
        $hashedToken = hash('sha256', $plainTextToken);

        DB::table('personal_access_tokens')->updateOrInsert(
            [
                'tokenable_id' => $user->id,
                'tokenable_type' => User::class,
                'name' => 'api-token',
            ],
            [
                'token' => $hashedToken,
                'abilities' => json_encode(['*']),
                'last_used_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
