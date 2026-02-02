<?php

namespace App\Http\Controllers\User\Auth\Login;

use App\Actions\Auth\LoginUser\LoginUser;
use App\Actions\Auth\LoginUser\LoginUserInput;
use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\User\Auth\Login\Login\Request\LoginRequestData;
use App\Data\User\Auth\Login\Login\Response\LoginResponseData;
use App\Http\Controllers\Controller;
use OpenApi\Attributes as OAT;

class LoginController extends Controller
{
    #[OAT\Post(path: '/users/auth/login/login', tags: ['usersAuth'])]
    #[JsonRequestBody(LoginRequestData::class)]
    #[SuccessItemResponse(LoginResponseData::class)]
    public function __invoke(LoginRequestData $request, LoginUser $loginUser)
    {

        $result =
            $loginUser
                ->handle(
                    new LoginUserInput(
                        $request->phone_number,
                        $request->password
                    )
                );

        return
            LoginResponseData::fromLoginUserResult(
                $result
            );

    }
}
