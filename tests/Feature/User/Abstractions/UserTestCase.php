<?php

namespace Tests\Feature\User\Abstractions;

use App\Helpers\RotueBuilder\RouteBuilder;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTestCase extends TestCase
{
    use RefreshDatabase;

    public User $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->route_builder =
                RouteBuilder::withMainRoute('users');

        $this->seed([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
        ]);

        // $this->createStudent();

        // $this->actingAs($this->student);
    }

    // private function createStudent(): void
    // {
    //     $this->student =
    //         User::query()
    //             ->has(relation: 'courses', operator: '>', count: 1)
    //             ->has('studentCourseRegisterations')
    //             ->first();
    // }

    public function getUser(): User
    {
        return
            User::factory()
                ->staticUser()
                ->create();

    }
}
