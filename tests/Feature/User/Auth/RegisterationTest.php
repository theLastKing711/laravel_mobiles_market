<?php

namespace Tests\Feature\User\Auth;

use App\Actions\Auth\CreateUser\CreateUser;
use App\Actions\Auth\CreateUser\CreateUserInput;
use App\Actions\Auth\CreateUser\CreateUserResult;
use App\Data\User\Auth\Registeration\AddPhoneNumberRegisterationStep\Request\AddPhoneNumberRegisterationStepRequestData;
use App\Data\User\Auth\Registeration\Register\Request\RegisterRequestData;
use App\Data\User\Auth\Registeration\Register\Response\RegisterResponseData;
use App\Enum\Auth\RolesEnum;
use App\Http\Controllers\User\Auth\Registeration\AddPhoneNumberRegisterationStepController;
use App\Http\Controllers\User\Auth\Registeration\RegisterController;
use App\Models\User;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\UserTestCase;

class RegisterationTest extends UserTestCase
{
    #[
        Test,
        Group(AddPhoneNumberRegisterationStepController::class),
        Group('success'),
        Group('200')
    ]
    public function phone_number_step_success_with_200_response(): void
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
        Group(AddPhoneNumberRegisterationStepController::class),
        Group('error'),
        Group('422')
    ]
    public function phone_number_step_sending_duplicated_phone_number_errors_with_422(): void
    {

        $duplicated_phone_number =
            fake()
                ->randomElement(
                    config('constants.store_users_numbers')
                );

        $new_user =
            User::factory()
                ->withPhoneAndPasswordAuth(
                    $duplicated_phone_number,
                    '2280'
                )
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
            ->assertStatus(422);

        $response
            ->assertOnlyJsonValidationErrors(
                [
                    'phone_number' => __(
                        'messages.users.auth.registeration.add-phone-number-step.phone_number.unique'
                    ),
                ]
            );

    }

    #[
        Test,
        Group(RegisterController::class),
        Group('success'),
        Group('201')
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
        Group(RegisterController::class),
        Group('error'),
        Group('422')
    ]
    public function register_user_with_duplicate_phone_number_errors_with_422(): void
    {

        $user =
            User::factory()
                ->user()
                ->create();

        $registeration_request_data =
            new RegisterRequestData(
                $user->phone_number,
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

        $response
            ->assertStatus(422);

        $response
            ->assertOnlyJsonValidationErrors(
                [
                    'phone_number' => __(
                        'messages.users.auth.registeration.add-phone-number-step.phone_number.unique'
                    ),
                ]
            );

    }
}
