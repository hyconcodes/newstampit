<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        
        User::where('email', 'bolaji.2782@bouesti.edu.ng')->update([
            'email_verified_at' => now()
        ]);

        // $user = User::create([
        //     'name' => 'Stampit Super Admin',
        //     'email' => 'superadmin@admin.com',
        //     'password' => bcrypt('password'),
        //     'email_verified_at' => now()
        // ]);

        // $user->assignRole('super admin');
    }
}
