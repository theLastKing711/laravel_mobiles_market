<?php

namespace Tests\Feature\User\Auth\Registeration;

use App\Data\User\Auth\Registeration\AddPhoneNumberRegisterationStep\Request\AddPhoneNumberRegisterationStepRequestData;
use App\Http\Controllers\User\Auth\Registeration\AddPhoneNumberRegisterationStepController;
use App\Models\User;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\UserTestCase;

class AddPhoneNumberRegisterationStepTest extends UserTestCase
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
}
