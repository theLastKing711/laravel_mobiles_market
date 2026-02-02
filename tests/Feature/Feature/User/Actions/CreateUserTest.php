<?php

namespace Tests\Feature\Feature\User\Actions;

use App\Actions\Auth\CreateUser\CreateUser;
use App\Actions\Auth\CreateUser\CreateUserInput;
use App\Enum\Auth\RolesEnum;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            RolesAndPermissionsSeeder::class,
        ]);

    }

    public static function create_user_success_data_provider()
    {

        return [
            'with_random_phone_number_creates_user_with_user_role' => [fn () => [
                'phone_number' => fake()->phoneNumber(),
                'password' => fake()->password(),
                'expected_role' => RolesEnum::USER,
            ],
            ],
            'with_store_phone_number_creates_user_with_store_role' => [fn () => [
                'phone_number' => fake()->randomElement(config('constants.store_users_numbers')),
                'password' => fake()->password(),
                'expected_role' => RolesEnum::STORE,
            ],
            ],
        ];

    }

    #[
        Test,
        DataProvider('create_user_success_data_provider')
    ]
    public function create_user_with_role_success($data_provider): void
    {

        $createUser = app(CreateUser::class);

        [
            'phone_number' => $phone_number,
            'password' => $password ,
            'expected_role' => $expected_role
        ] = $data_provider();

        $result =
             $createUser
                 ->handle(
                     new CreateUserInput(
                         $phone_number,
                         $password
                     )
                 );

        $this
            ->assertDatabaseCount(
                User::class,
                1
            );

        $this
            ->assertDatabaseHas(
                User::class,
                [
                    'phone_number' => $phone_number,
                ]
            );

        $created_user_is_user =
            User::query()
                ->firstWhere(
                    'phone_number',
                    $phone_number
                )
                ->hasRole(
                    $expected_role
                );

        $this
            ->assertTrue(
                $created_user_is_user
            );

    }
}
