<?php

namespace Tests\Feature\User\Abstractions;

use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\StoreTrait;

class StoreTestCase extends TestCase
{
    use RefreshDatabase, StoreTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            RolesAndPermissionsSeeder::class,
        ]);

        $this
            ->initializeStore();
    }
}
