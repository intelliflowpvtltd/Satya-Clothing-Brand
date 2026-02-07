<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@ecommerce.com',
            'password' => Hash::make('Admin@123'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }
}
