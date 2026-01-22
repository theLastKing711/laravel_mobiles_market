<?php

namespace Tests\Feature\User\Abstractions;

use App\Helpers\RotueBuilder\RouteBuilder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->route_builder =
                RouteBuilder::withMainRoute('users');

        $this->seed([
            RolesAndPermissionsSeeder::class,
        ]);
    }
}
