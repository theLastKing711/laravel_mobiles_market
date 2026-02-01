<?php

namespace Tests\Feature\File\Abstractions;

use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\StoreTrait;

class FileTestCase extends TestCase
{
    use RefreshDatabase, StoreTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            RolesAndPermissionsSeeder::class,
        ]);

        $this->initializeStore();

    }
}
