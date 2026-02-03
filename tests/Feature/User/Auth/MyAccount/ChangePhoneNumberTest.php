<?php

namespace Tests\Feature\User\Auth\MyAccount;

use App\Data\User\Auth\ChangePhoneNumber\Request\ChangePhoneNumberRequestData;
use App\Http\Controllers\User\Auth\ChangePhoneNumberController;
use App\Models\User;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\UserTestCase;

class ChangePhoneNumberTest extends UserTestCase
{
    #[
        Test,
        Group(ChangePhoneNumberController::class),
        Group('success'),
        Group('200')
    ]
    public function change_password_success_with_200(): void
    {

        $user_phone_number =
            $this
                ->user
                ->phone_number;

        $request =
            new ChangePhoneNumberRequestData(
                $user_phone_number
            );

        $response =
           $this
               ->withRouteName(
                   route(
                       'users.auth.change-phone-number'
                   )
               )
               ->patchJsonData(
                   $request
                       ->toArray()
               );

        $response
            ->assertStatus(200);

        $this->assertNotNull($user_phone_number);

        $this
            ->assertDatabaseHas(
                User::class,
                [
                    'id' => $this->user->id,
                    'phone_number' => $user_phone_number,
                ]
            );

    }

    #[
        Test,
        Group(ChangePhoneNumberController::class),
        Group('error'),
        Group('422')
    ]
    public function change_password_success_with_phone_number_for_other_user_error_with_422(): void
    {

        $user_phone_number =
            User::factory()
                ->user()
                ->create()
                ->phone_number;

        $request =
            new ChangePhoneNumberRequestData(
                $user_phone_number
            );

        $response =
           $this
               ->withRouteName(
                   route(
                       'users.auth.change-phone-number'
                   )
               )
               ->patchJsonData(
                   $request
                       ->toArray()
               );

        $response
            ->assertStatus(422);

    }
}
