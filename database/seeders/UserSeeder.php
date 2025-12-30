<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedUsers();

    }

    public function seedUsers(): void
    {

        User::factory()
            ->staticUser()
            ->create();

        // User::factory()
        //     ->count(9)
        //     ->admin()
        //     ->create();
    }
}
