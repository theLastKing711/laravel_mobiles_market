<?php

namespace App\Http\Controllers\User\Auth\Registeration;

use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\User\Auth\Registeration\Register\Request\RegisterRequestData;
use App\Data\User\Auth\Registeration\Register\Response\RegisterResponseData;
use App\Enum\Auth\RolesEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use OpenApi\Attributes as OAT;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    #[OAT\Post(path: '/users/auth/registeration/register', tags: ['usersAuth'])]
    #[JsonRequestBody(RegisterRequestData::class)]
    #[SuccessItemResponse(RegisterResponseData::class)]
    public function __invoke(RegisterRequestData $createPasswordRequestData)
    {

        /** @var array<string> $store_users_numbers */
        $store_users_numbers = config('constants.store_users_numbers');

        $request_phone_number = $createPasswordRequestData->phone_number;

        $user =
            User::query()
                ->create(attributes: [
                    // 'country_code' => '963',
                    // 'fcm_token' => $createPasswordRequestData->fcm_token,
                    'phone_number' => $request_phone_number,
                    'password' => $createPasswordRequestData->password,
                ]);

        $user_is_store =
             in_array($request_phone_number, $store_users_numbers);

        if (! $user_is_store) {
            $user->assignRole(RolesEnum::USER);
        }

        if ($user_is_store) {
            $user->assignRole(RolesEnum::STORE);
        }

        $token =
            $user
                ->createToken($request_phone_number)
                ->plainTextToken;

        /** @var Role $user_role description */
        $user_role =
                $user
                    ->roles()
                    ->first();

        return
            RegisterResponseData::from([
                'token' => $token,
                'role' => $user_role->name,
            ]);

        // return response(
        //     new RegisterResponseData($token),
        //     201
        // );

    }
}
