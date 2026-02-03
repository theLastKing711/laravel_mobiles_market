<?php

namespace Tests\Feature\User\Auth\Login;

use App\Data\User\Auth\Login\AddPhoneNumberLoginStep\Request\AddPhoneNumberLoginStepRequestData;
use App\Http\Controllers\User\Auth\Login\AddPhoneNumberLoginStepController;
use App\Models\User;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\UserTestCase;

class AddPhoneNumberLoginStepTest extends UserTestCase
{
    #[
        Test,
        Group(AddPhoneNumberLoginStepController::class),
        Group('success'),
        Group('200')
    ]
    public function login_phone_number_step_success_with_200(): void
    {

        $user =
            User::factory()
                ->user()
                ->create();

        $login_phone_number_step_request_data =
            new AddPhoneNumberLoginStepRequestData(
                $user->phone_number
            );

        $response =
           $this
               ->withRouteName(
                   route(
                       'users.auth.login.phone-number-step'
                   )
               )
               ->postJsonData(
                   $login_phone_number_step_request_data
                       ->toArray()
               );

        $response
            ->assertStatus(
                200
            );

    }

    #[
        Test,
        Group(AddPhoneNumberLoginStepController::class),
        Group('error'),
        Group('422')
    ]
    public function login_phone_number_step_entering_non_existing_phone_number_errors_with_422_response(): void
    {

        $login_phone_number_steprequest_data =
            new AddPhoneNumberLoginStepRequestData(
                phone_number: fake()
                    ->phoneNumber()
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

        $response
            ->assertStatus(422);

        $response
            ->assertOnlyJsonValidationErrors(
                [
                    'phone_number' => __(
                        'messages.users.auth.login.add-phone-number-step.phone_number.exist'
                    ),
                ]
            );

    }
}
