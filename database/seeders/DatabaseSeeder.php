<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        //creating roles
        $adminRole = Role::create(['name' => 'Super Admin']);
        $employeeRole = Role::create(['name' => 'employee']);
        $customerRole = Role::create(['name' => 'customer']);
        //creating permissions
        Permission::create(['name' => 'view dashboard']);
        Permission::create(['name' => 'manage products']);
        Permission::create(['name' => 'manage orders']);
        Permission::create(['name' => 'manage customers']);
        Permission::create(['name' => 'manage employees']);
        Permission::create(['name' => 'manage reviews']);
        Permission::create(['name' => 'make reviews']);
        Permission::create(['name' => 'make orders']);
        //giving permissions to the roles
        $adminRole->givePermissionTo(Permission::all());
        $employeeRole->givePermissionTo([
            'view dashboard',
            'manage products',
            'manage orders',
            'manage customers',
            'manage reviews',
            'make reviews',
            'make orders',
        ]);
        // $customerRole->givePermissionTo([
        //     'make reviews',
        //     'make orders'
        // ]);


        User::factory()->create([
                      'name' => 'Adawy',
                      'email' => 'hussien@gmail.com',
                  ])->assignRole($adminRole);
        User::factory()->create([
                      'name' => 'ahmed',
                      'email' => 'ahmed@gmail.com',
                  ])->assignRole($employeeRole);
        User::factory()->create([
                      'name' => 'omar',
                      'email' => 'omar@gmail.com',
                  ])->assignRole($employeeRole);


        User::factory(10)->customer()->create();
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
