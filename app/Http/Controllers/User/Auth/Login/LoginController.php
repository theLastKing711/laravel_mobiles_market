<?php

namespace App\Http\Controllers\User\Auth\Login;

use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\User\Auth\Login\Login\Request\LoginRequestData;
use App\Data\User\Auth\Login\Login\Response\LoginResponseData;
use App\Http\Controllers\Controller;
use App\Models\User;
use Cloudinary\Api\HttpStatusCode;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OAT;
use Spatie\Permission\Models\Role;

class LoginController extends Controller
{
    #[OAT\Post(path: '/users/auth/login/login', tags: ['usersAuth'])]
    #[JsonRequestBody(LoginRequestData::class)]
    #[SuccessItemResponse(LoginResponseData::class)]
    public function __invoke(LoginRequestData $request)
    {

        $request_phone_number =
            $request
                ->phone_number;

        $authenticated_user =
            User::query()
                ->firstWhere(
                    [
                        'phone_number' => $request_phone_number,
                        // 'password' => $request->password,
                    ]
                );

        if (! $authenticated_user) {
            return response(
                [
                    'message' => 'كلمة المرور لرقم الهاتف غير صحيحة',
                ],
                HttpStatusCode::UNAUTHORIZED
            );
        }

        if ($authenticated_user && ! Hash::check($request->password, $authenticated_user->password)) {

            // if ($authenticated_user === null) {

            return response(
                [
                    'message' => 'كلمة المرور لرقم الهاتف غير صحيحة',
                ],
                HttpStatusCode::UNAUTHORIZED
            );
        }

        $token =
            $authenticated_user
                ->createToken(
                    $request_phone_number
                );

        /** @var Role $user_role description */
        $user_role =
            $authenticated_user
                ->roles
                ->first();

        return response(
            new LoginResponseData(
                $token->plainTextToken,
                $user_role->name
            ),
            200
        );

    }
}
