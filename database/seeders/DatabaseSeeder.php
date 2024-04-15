<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Haytham',
            'email' => 'haytham@boca.pro',
            'password' => Hash::make('qwerty123'),
            'is_admin' => true,
        ]);

        Category::factory()->create([
            'name' => 'Mobile Phones',
        ]);
    }
}
