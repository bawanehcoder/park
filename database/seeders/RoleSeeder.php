<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the roles
        $roles = [ 'owner', 'supervisor', 'driver'];

        foreach ($roles as $role) {
            // Check if the role already exists to avoid duplication
            Role::firstOrCreate(['name' => $role, 'guard_name'=>'web']);
        }
    }
}
