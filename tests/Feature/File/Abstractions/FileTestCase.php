<?php

namespace Tests\Feature\File\Abstractions;

use App\Helpers\RotueBuilder\RouteBuilder;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FileTestCase extends TestCase
{
    use RefreshDatabase;

    public User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->route_builder =
                RouteBuilder::withMainRoute('files');

        $this->seed([
            RolesAndPermissionsSeeder::class,
        ]);

        // $this->createStudent();

        $this->user = $this->getUser();

        $this->actingAs($this->user);
    }

    public function getUser(): User
    {
        return
            User::factory()
                ->user()
                ->create();

    }
}
