<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Todo;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create one test user
        $user = User::factory()->create([
            'name' => 'Hasan Khan',
            'email' => 'hasan_912000@yahoo.com',
            'password' => bcrypt('12345678'),
        ]);

        // Create one test todo
        Todo::create([
            'user_id' => $user->id,
            'title' => 'Welcome to your Todo list!',
            'completed' => false,
        ]);
    }
}

