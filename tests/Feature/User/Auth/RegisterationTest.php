<?php

namespace Tests\Feature\User\Auth;

use App\Actions\Auth\CreateUser\CreateUser;
use App\Actions\Auth\CreateUser\CreateUserInput;
use App\Actions\Auth\CreateUser\CreateUserResult;
use App\Data\User\Auth\Registeration\AddPhoneNumberRegisterationStep\Request\AddPhoneNumberRegisterationStepRequestData;
use App\Data\User\Auth\Registeration\Register\Request\RegisterRequestData;
use App\Data\User\Auth\Registeration\Register\Response\RegisterResponseData;
use App\Enum\Auth\RolesEnum;
use App\Models\User;
use Cloudinary\Api\HttpStatusCode;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\UserTestCase;

class RegisterationTest extends UserTestCase
{
    #[
        Test,
        Group('phone-number-step'),
        Group('success')
    ]
    public function create_phone_number_in_registeration_success_with_200_response(): void
    {

        $registeration_step_request_data =
            new AddPhoneNumberRegisterationStepRequestData(
                phone_number: fake()->phoneNumber()
            );

        $response =
           $this
               ->withRouteName(
                   route(
                       'users.auth.registeration.phone-number-step'
                   )
               )
               ->postJsonData(
                   $registeration_step_request_data
                       ->toArray()
               );

        $response
            ->assertStatus(200);

    }

    #[
        Test,
        Group('phone-number-step'),
        Group('error')
    ]
    public function create_duplicated_phone_number_in_registeration_errors_with_409_response(): void
    {

        $new_user =
            User::factory()
                ->withPhoneAndPasswordAuth('0968259851', '2280')
                ->create();

        $registeration_step_request_data =
            new AddPhoneNumberRegisterationStepRequestData(
                $new_user->phone_number
            );

        $response =
           $this
               ->withRouteName(
                   route(
                       'users.auth.registeration.phone-number-step'
                   )
               )
               ->postJsonData(
                   $registeration_step_request_data
                       ->toArray()
               );

        $response
            ->assertStatus(HttpStatusCode::CONFLICT);

    }

    #[
        Test,
        Group('register'),
        Group('success')
    ]
    public function register_user_success_with_201_response(): void
    {

        $registeration_request_data =
            new RegisterRequestData(
                fake()->phoneNumber(),
                fake()->password()
            );

        $create_user_result =
            new CreateUserResult(
                fake()->name(),
                fake()->randomElement(RolesEnum::cases())->value
            );

        $this
            ->mock(
                CreateUser::class,
                function (MockInterface $mock) use ($create_user_result, $registeration_request_data) {
                    $mock
                        ->expects('handle')
                        ->withArgs(
                            fn (CreateUserInput $input) => $input->phone_number === $registeration_request_data->phone_number
                        &&
                        $input->password === $registeration_request_data->password

                        )
                        ->andReturn(
                            $create_user_result
                        );
                });

        $response =
           $this
               ->withRouteName(
                   route(
                       'users.auth.registeration.register'
                   )
               )
               ->postJsonData(
                   $registeration_request_data
                       ->toArray()
               );

        $response
            ->assertStatus(201);

        $resposne_data =
            RegisterResponseData::from(
                $response
                    ->json()
            );

        $this
            ->assertEquals(
                $resposne_data->token,
                $create_user_result->token
            );

        $this
            ->assertEquals(
                $resposne_data->role,
                $create_user_result->role
            );
    }

    #[
        Test,
        Group('register'),
        Group('success')
    ]
    public function register_store_success_with_201_response(): void
    {

        /** @var array<string> $store_users_numbers */
        $store_users_numbers = config('constants.store_users_numbers');

        $store_phone_number =
            collect($store_users_numbers)
                ->first();

        $registeration_request_data =
            new RegisterRequestData(
                $store_phone_number,
                fake()->password()
            );

        $response =
           $this
               ->withRouteName(
                   route(
                       'users.auth.registeration.register'
                   )
               )
               ->postJsonData(
                   $registeration_request_data
                       ->toArray()
               );

        $response->assertStatus(201);

        $this
            ->assertDatabaseCount(
                User::class,
                1
            );

        $this
            ->assertDatabaseHas(
                User::class,
                [
                    'phone_number' => $registeration_request_data->phone_number,
                ]
            );

        $created_user_is_user =
            User::query()
                ->firstWhere(
                    'phone_number',
                    $registeration_request_data->phone_number
                )
                ->hasRole(RolesEnum::STORE);

        $this
            ->assertTrue(
                $created_user_is_user
            );
    }
}
