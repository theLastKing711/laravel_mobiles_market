<?php

namespace Tests\Feature\User\Abstractions;

use App\Helpers\RotueBuilder\RouteBuilder;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTestCase extends TestCase
{
    use RefreshDatabase;

    public User $user;

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

    public function getUser(): User
    {
        return
            User::factory()
                ->user()
                ->create();

    }

    public function initializeUser()
    {

        $this->user =
           $this
               ->getUser();

        $this->actingAs($this->user);
    }
}
