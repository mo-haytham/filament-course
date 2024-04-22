<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Tag;
use App\Models\Team;
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
        $team = Team::create([
            'name' => 'Boca',
            'slug' => 'boca',
        ]);

        User::create([
            'name' => 'Haytham',
            'email' => 'haytham@boca.pro',
            'password' => Hash::make('qwerty123'),
            'is_admin' => true,
        ])->teams()->attach($team);

        $category = Category::create([
            'name' => 'Mobile Phones',
            'team_id' => $team->id,
        ]);

        $tag = Tag::create([
            'name' => 'IOS',
            'team_id' => $team->id,
        ]);

        Product::create([
            'name' => 'Iphone 14',
            'description' => 'This is a mobile phone',
            'price' => 100000,
            'category_id' => $category->id,
            'team_id' => $team->id,
            'slug' => 'iphone-14',
        ])->tags()->attach($tag);

        Order::create([
            'user_id' => 1,
            'product_id' => 1,
            'team_id' => $team->id,
        ]);
    }
}
