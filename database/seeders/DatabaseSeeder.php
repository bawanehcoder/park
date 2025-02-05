<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        

        // $this->call(companySeeder::class);
        // $this->call(PlanSeeder::class);
        // $this->call(ParkingSeeder::class);
        // $this->call(SlotSeeder::class);
        // $this->call(BookingSeeder::class);
        // $this->call(CarSeeder::class);

        User::factory()
            ->count(1)
            ->create([
                'email' => 'admin@admin.com',
                'password' => \Hash::make('admin'),
            ]);


        $this->call(RoleSeeder::class);

    }
}
