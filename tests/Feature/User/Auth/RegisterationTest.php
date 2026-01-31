<?php

namespace Tests\Feature\User\Auth;

use App\Data\User\Auth\Registeration\AddPhoneNumberRegisterationStep\Request\AddPhoneNumberRegisterationStepRequestData;
use App\Data\User\Auth\Registeration\Register\Request\RegisterRequestData;
use App\Enum\Auth\RolesEnum;
use App\Models\User;
use Cloudinary\Api\HttpStatusCode;
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

        $response->assertStatus(200);

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

        $response->assertStatus(HttpStatusCode::CONFLICT);

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
            ->assertDatabaseHas(
                User::class,
                [
                    'phone_number' => $registeration_request_data->phone_number,
                ]
            );

        $created_user_is_user =
            User::query()
                ->where(
                    'phone_number',
                    $registeration_request_data->phone_number
                )
                ->first()
                ->hasRole(RolesEnum::USER);

        $this
            ->assertTrue(
                $created_user_is_user
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
