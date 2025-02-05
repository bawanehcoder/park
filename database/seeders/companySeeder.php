<?php

namespace Database\Seeders;

use App\Models\company;
use Illuminate\Database\Seeder;

class companySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        company::factory()
            ->count(5)
            ->create();
    }
}
