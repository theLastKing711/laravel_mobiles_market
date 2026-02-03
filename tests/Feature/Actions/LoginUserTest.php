<?php

namespace Tests\Feature\Actions;

use App\Actions\Auth\loginUser\loginUser;
use App\Actions\Auth\LoginUser\LoginUserInput;
use App\Enum\Auth\RolesEnum;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class LoginUserTest extends TestCase
{
    const TESTED_CLASS = LoginUser::class;

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            RolesAndPermissionsSeeder::class,
        ]);

    }

    public static function valid_phone_number_and_password_provider(): array
    {

        return [
            'with user role' => [
                function (): array {
                    $password =
                        'user';
                    $user =
                        User::factory()
                            ->withPassword($password)
                            ->user()
                            ->create();

                    return [
                        'phone_number' => $user->phone_number,
                        'password' => $password,
                        'expected_role' => RolesEnum::USER->value,
                    ];
                },
            ],
            'with store role' => [
                function (): array {
                    $password =
                        'user';
                    $user =
                        User::factory()
                            ->withPassword($password)
                            ->storeUser()
                            ->create();

                    return [
                        'phone_number' => $user->phone_number,
                        'password' => $password,
                        'expected_role' => RolesEnum::STORE->value,
                    ];
                },
            ],
            'with admin role' => [
                function (): array {
                    $password =
                        'user';
                    $user =
                        User::factory()
                            ->withPassword($password)
                            ->admin()
                            ->create();

                    return [
                        'phone_number' => $user->phone_number,
                        'password' => $password,
                        'expected_role' => RolesEnum::ADMIN->value,
                    ];
                },
            ],
        ];

    }

    #[
        Test,
        Group(LoginUser::class),
        Group('success'),
        DataProvider('valid_phone_number_and_password_provider')
    ]
    public function login_user_with_valid_credintials_success($data_provider): void
    {

        $loginUser = app(LoginUser::class);

        [
            'phone_number' => $phone_number,
            'password' => $password,
            'expected_role' => $expected_role
        ] = $data_provider();

        $result =
             $loginUser
                 ->handle(
                     new LoginUserInput(
                         $phone_number,
                         $password
                     )
                 );

        $this->assertNotNull($result->token);

        $this
            ->assertEquals(
                $result->role,
                $expected_role
            );

    }

    public static function invalid_phone_number_and_password_provider(): array
    {

        return [
            'with_wrong_phone_number' => [
                function (): array {
                    $user =
                        User::factory()
                            ->user()
                            ->create();

                    return [
                        'phone_number' => $user->phone_number,
                        'password' => 'wrong_password',
                    ];

                },
            ],
            'with_wrong_password' => [
                function (): array {
                    $user =
                        User::factory()
                            ->user()
                            ->create();

                    return [
                        'phone_number' => 'wrong_username',
                        'password' => $user->password,
                    ];
                },
            ],
            'with_wrong_phone_number_and_password' => [
                function (): array {
                    User::factory()
                        ->user()
                        ->create();

                    return [
                        'phone_number' => 'wrong_username',
                        'password' => 'wrong_password',
                    ];
                },
            ],
        ];

    }

    #[
        Test,
        Group(LoginUser::class),
        Group('error'),
        DataProvider('invalid_phone_number_and_password_provider')
    ]
    public function login_user_with_invalid_credintials_throws_401_http_exception($data_provider): void
    {

        $loginUser = app(LoginUser::class);

        [
            'phone_number' => $phone_number,
            'password' => $password,
        ] = $data_provider();

        $this
            ->expectException(
                HttpException::class
            );

        $loginUser
            ->handle(
                new LoginUserInput(
                    $phone_number,
                    $password
                )
            );

    }
}
