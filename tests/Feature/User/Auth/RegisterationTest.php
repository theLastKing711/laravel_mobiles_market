<?php

namespace Tests\Feature\User\Auth;

use App\Data\User\Auth\Registeration\AddPhoneNumberRegisterationStep\Request\AddPhoneNumberRegisterationStepRequestData;
use App\Data\User\Auth\Registeration\Register\Request\RegisterRequestData;
use App\Enum\Auth\RolesEnum;
use App\Models\User;
use Cloudinary\Api\HttpStatusCode;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\UserTestCase;

class RegisterationTest extends UserTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->withRoutePaths(
                'auth',
                'registeration'
            );

    }

    #[Test]
    public function create_phone_number_in_registeration_success_with_200_response(): void
    {

        $this
            ->withRoutePaths(
                'phone-number-step'
            );

        $registeration_step_request_data =
            new AddPhoneNumberRegisterationStepRequestData(
                phone_number: fake()->phoneNumber()
            );

        $response =
           $this
               ->postJsonData(
                   $registeration_step_request_data
                       ->toArray()
               );

        $response->assertStatus(200);

    }

    #[Test]
    public function create_duplicated_phone_number_in_registeration_errors_with_409_response(): void
    {

        $this
            ->withRoutePaths(
                'phone-number-step'
            );

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
               ->postJsonData(
                   $registeration_step_request_data
                       ->toArray()
               );

        $response->assertStatus(HttpStatusCode::CONFLICT);

    }

    #[Test]
    public function register_user_success_with_201_response(): void
    {

        $this
            ->withRoutePaths(
                'register'
            );

        $registeration_request_data =
            new RegisterRequestData(
                fake()->phoneNumber(),
                fake()->password()
            );

        $response =
           $this
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

    #[Test]
    public function register_store_success_with_201_response(): void
    {

        /** @var array<string> $store_users_numbers */
        $store_users_numbers = config('constants.store_users_numbers');

        $this
            ->withRoutePaths(
                'register'
            );

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
