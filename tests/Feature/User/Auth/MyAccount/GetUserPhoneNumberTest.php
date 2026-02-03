<?php

namespace Tests\Feature\User\Auth\MyAccount;

use App\Http\Controllers\User\Auth\GetUserPhoneNumberController;
use App\Models\User;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\UserTestCase;

class GetUserPhoneNumberTest extends UserTestCase
{
    #[
        Test,
        Group(GetUserPhoneNumberController::class),
        Group('success'),
        Group('200')
    ]
    public function get_user_phone_number_success_with_200(): void
    {

        $user =
            User::factory()
                ->user()
                ->create();

        $this
            ->actingAs($user);

        $response =
           $this
               ->withRouteName(
                   route(
                       'users.auth.get-user-phone-number'
                   )
               )
               ->getJsonData();

        $response
            ->assertStatus(200);

        $response
            ->assertJsonPath(
                'phone_number',
                $user->phone_number
            );

    }
}
