<?php

namespace Tests\Feature\User\Auth\MyAccount;

use App\Data\User\Auth\ChangePassword\Request\ChangePasswordRequestData;
use App\Http\Controllers\User\Auth\ChangePasswordController;
use App\Models\User;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\UserTestCase;

class ChangePasswordTest extends UserTestCase
{
    #[
        Test,
        Group(ChangePasswordController::class),
        Group('success'),
        Group('200')
    ]
    public function change_password_success_with_200(): void
    {

        $password =
            'password';

        $user =
            User::factory()
                ->user()
                ->withPassword($password)
                ->create();

        $request =
            new ChangePasswordRequestData(
                $password
            );

        $this
            ->actingAs($user);

        $response =
           $this
               ->withRouteName(
                   route(
                       'users.auth.change-password'
                   )
               )
               ->patchJsonData(
                   $request
                       ->toArray()
               );

        $response
            ->assertStatus(200);

    }
}
