<?php

namespace Tests\Feature\User\Auth;

use App\Actions\Auth\LoginUser\LoginUser;
use App\Actions\Auth\LoginUser\LoginUserInput;
use App\Actions\Auth\LoginUser\LoginUserResult;
use App\Data\User\Auth\Login\AddPhoneNumberLoginStep\Request\AddPhoneNumberLoginStepRequestData;
use App\Data\User\Auth\Login\Login\Request\LoginRequestData;
use App\Enum\Auth\RolesEnum;
use App\Models\User;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\Feature\User\Abstractions\UserTestCase;

class LoginTest extends UserTestCase
{
    #[
        Test,
        Group('phone-number-step'),
        Group('error')
    ]
    public function enter_non_existing_phone_number_in_login_phone_step_errors_with_404_response(): void
    {

        $login_phone_number_steprequest_data =
            new AddPhoneNumberLoginStepRequestData(
                phone_number: fake()->phoneNumber()
            );

        $response =
           $this
               ->withRouteName(
                   route(
                       'users.auth.login.phone-number-step'
                   )
               )
               ->postJsonData(
                   $login_phone_number_steprequest_data
                       ->toArray()
               );

        $response->assertStatus(404);

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
                    ];
                },
            ],
        ];

    }

    #[
        Test,
        Group('login'),
        Group('success'),
    ]
    public function login_with_valid_credintials_success_with_201(): void
    {
        $password =
            'user';

        $new_user =
            User::factory()
                ->user()
                ->withPassword($password)
                ->create();

        $login_request_data =
            new LoginRequestData(
                $new_user->phone_number,
                $password
            );

        $login_user_result =
             new LoginUserResult(
                 'random_token',
                 RolesEnum::USER->value
             );

        $this
            ->mock(
                LoginUser::class,
                function (MockInterface $mock) use ($login_request_data, $login_user_result) {
                    $mock
                        ->expects('handle')
                        ->withArgs(
                            function (LoginUserInput $loginUserInput) use ($login_request_data) {
                                return
                                    $loginUserInput->phone_number === $login_request_data->phone_number
                                        &&
                                    $loginUserInput->password === $login_request_data->password;
                            }
                        )
                        ->andReturn(
                            $login_user_result
                        );
                });

        $response =
           $this
               ->withRouteName(
                   route(
                       'users.auth.login.login'
                   )
               )
               ->postJsonData(
                   $login_request_data
                       ->toArray()
               );

        $response->assertStatus(201);

    }

    /**
     * @param  callable(): LoginRequestData  $request
     **/
    #[
        Test,
        Group('login'),
        Group('error with 401 status'),
    ]
    public function login_with_wrong_credintials_error_with_401(): void
    {
        $new_user =
            User::factory()
                ->create();

        $login_request_data =
            new LoginRequestData(
                $new_user->phone_number,
                'wrong password'
            );

        $this
            ->mock(
                LoginUser::class,
                function (MockInterface $mock) use ($login_request_data) {
                    $mock
                        ->expects('handle')
                        ->withArgs(
                            function (LoginUserInput $loginUserInput) use ($login_request_data) {
                                return
                                    $loginUserInput->phone_number === $login_request_data->phone_number
                                        &&
                                    $loginUserInput->password === $login_request_data->password;
                            }
                        )
                        ->andThrow(
                            new HttpException(401)
                        );

                });

        $response =
           $this
               ->withRouteName(
                   route(
                       'users.auth.login.login'
                   )
               )
               ->postJsonData(
                   $login_request_data
                       ->toArray()
               );

        $response->assertStatus(401);

    }

    #[
        Test,
        Group('login'),
        Group('error with 422 status'),
    ]
    public function login_with_non_existing_phone_number_error_with_422(): void
    {
        $new_user =
            User::factory()
                ->create();

        $login_request_data =
            new LoginRequestData(
                fake()->phoneNumber(),
                $new_user->password
            );

        $response =
           $this
               ->withRouteName(
                   route(
                       'users.auth.login.login'
                   )
               )
               ->postJsonData(
                   $login_request_data
                       ->toArray()
               );

        $response->assertStatus(422);

    }
}
