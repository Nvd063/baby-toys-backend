<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        User::create([
            'name' => 'Admin',
            'email' => 'admin@toys.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
        User::create([
            'name' => 'User',
            'email' => 'user@toys.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);

        // Categories, Subs, Products
        $categories = ['Baby Toys', 'Educational Toys', 'Outdoor Toys'];
        foreach ($categories as $name) {
            $category = Category::create(['name' => $name]);
            $subs = ['Rattles', 'Puzzles', 'Balls'];
            foreach ($subs as $subName) {
                $subCategory = $category->subCategories()->create(['name' => $subName]);
                for ($i = 1; $i <= 5; $i++) {
                    $subCategory->products()->create([
                        'name' => $subName . ' #' . $i,
                        'price' => rand(5, 50) + (rand(0, 99) / 100),
                        'description' => 'Fun ' . $subName . ' toy for babies #' . $i . '.',
                        'image' => 'products/dummy.jpg', // Placeholder
                    ]);
                }
            }
        }
    }
}