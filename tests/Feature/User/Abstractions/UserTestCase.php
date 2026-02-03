<?php

namespace Tests\Feature\User\Abstractions;

use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\UserTrait;

class UserTestCase extends TestCase
{
    use RefreshDatabase, UserTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            RolesAndPermissionsSeeder::class,
        ]);

        $this
            ->initializeUser();
    }
}
