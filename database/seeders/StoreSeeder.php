<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedStores();

    }

    public function seedStores(): void
    {

        User::factory()
            ->staticStore()
            ->create();

    }
}
