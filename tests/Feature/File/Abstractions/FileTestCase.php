<?php

namespace Tests\Feature\File\Abstractions;

use App\Helpers\RotueBuilder\RouteBuilder;
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

        $this
            ->route_builder =
                RouteBuilder::withMainRoute(
                    'files'
                );

        $this->seed([
            RolesAndPermissionsSeeder::class,
        ]);

        $this->initializeStore();

    }
}
