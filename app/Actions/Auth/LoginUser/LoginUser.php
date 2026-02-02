<?php

namespace App\Actions\Auth\LoginUser;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Tests\Feature\Feature\User\Actions\LoginUserTest;

class LoginUser
{
    const TEST_CLASS = LoginUserTest::class;

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     **/
    public function handle(
        LoginUserInput $login_user_input
    ) {

        $request_phone_number =
            $login_user_input
                ->phone_number;

        if (
            Auth::attempt([
                'phone_number' => $request_phone_number,
                'password' => $login_user_input->password,
            ])
        ) {
            $authenticated_user =
                Auth::user();

            $token = $authenticated_user
                ->createToken(
                    $request_phone_number
                );

            /** @var Role $user_role description */
            $user_role =
                $authenticated_user
                    ->roles
                    ->first();

            return
                new LoginUserResult(
                    $token->plainTextToken,
                    $user_role->name
                );
        }

        abort(401);

    }
}
