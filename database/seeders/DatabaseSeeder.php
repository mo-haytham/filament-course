<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\Order;
use App\Models\Product;
use App\Models\Tag;
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
        $company = Company::create([
            'name' => 'Boca',
        ]);

        User::create([
            'name' => 'Haytham',
            'email' => 'haytham@boca.pro',
            'password' => Hash::make('qwerty123'),
            'is_admin' => true,
        ])->companies()->attach($company);

        $category = Category::create([
            'name' => 'Mobile Phones',
            'company_id' => $company->id,
        ]);

        $tag = Tag::create([
            'name' => 'IOS',
            'company_id' => $company->id,
        ]);

        Product::create([
            'name' => 'Iphone 14',
            'description' => 'This is a mobile phone',
            'price' => 100000,
            'category_id' => $category->id,
            'company_id' => $company->id,
            'slug' => 'iphone-14',
        ])->tags()->attach($tag);

        Order::create([
            'user_id' => 1,
            'product_id' => 1,
            'company_id' => $company->id,
        ]);
    }
}
