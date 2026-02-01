<?php

namespace App\Actions\Auth\CreateUser;

use App\Enum\Auth\RolesEnum;
use App\Models\User;

class CreateUser
{
    public function handle(
        CreateUserInput $createUserInput
    ) {
        $store_users_numbers = config('constants.store_users_numbers');

        $phone_number =
            $createUserInput
                ->phone_number;

        $password =
            $createUserInput
                ->password;
        $user =
            User::query()
                ->create(attributes: [
                    'phone_number' => $phone_number,
                    'password' => $password,
                ]);

        $user_is_store =
             in_array($phone_number, $store_users_numbers);

        if (! $user_is_store) {
            $user->assignRole(RolesEnum::USER);
        }

        if ($user_is_store) {
            $user->assignRole(RolesEnum::STORE);
        }

        $token =
            $user
                ->createToken($phone_number)
                ->plainTextToken;

        $user_role =
                $user
                    ->roles()
                    ->first();

        return
            new CreateUserResult(
                $token,
                $user_role
            );

    }
}
