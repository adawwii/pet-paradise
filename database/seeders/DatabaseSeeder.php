<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Review;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         User::factory()->create([
            'name' => 'Adawy',
            'email' => 'hussien@gmail.com',
            'role'=>'admin'
        ]);
        User::factory()->create([
            'name' => 'ahmed',
            'email' => 'ahmed@gmail.com',
            'role'=>'employee'
        ]);
        User::factory()->create([
            'name' => 'omar',
            'email' => 'omar@gmail.com',
            'role'=>'employee'
        ]);


        User::factory(10)->create();
        Category::create([
            
                'name' => 'Dogs',
                'description' => 'Food, toys and accessories for dogs',
                'created_at' => now(),
                'updated_at' => now(),
            
            ]);
            Category::create([
                'name' => 'Cats',
                'description' => 'Food, toys and accessories for cats',
                'created_at' => now(),
                'updated_at' => now(),
            
            ]);
            Category::create([
                'name' => 'Birds',
                'description' => 'Cages, food and accessories for birds',
                'created_at' => now(),
                'updated_at' => now(),
            
            ]);
            Category::create([
                'name' => 'Fish',
                'description' => 'Aquariums and fish food',
                'created_at' => now(),
                'updated_at' => now(),
            
        ]);
        Product::factory(10)->create();
        Review::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

    }
}
