<?php

namespace App\Http\Controllers\User\Auth\Registeration;

use App\Actions\Auth\CreateUser\CreateUser;
use App\Actions\Auth\CreateUser\CreateUserInput;
use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\User\Auth\Registeration\Register\Request\RegisterRequestData;
use App\Data\User\Auth\Registeration\Register\Response\RegisterResponseData;
use App\Http\Controllers\Controller;
use OpenApi\Attributes as OAT;

class RegisterController extends Controller
{
    #[OAT\Post(path: '/users/auth/registeration/register', tags: ['usersAuth'])]
    #[JsonRequestBody(RegisterRequestData::class)]
    #[SuccessItemResponse(RegisterResponseData::class)]
    public function __invoke(RegisterRequestData $request, CreateUser $createUser)
    {

        $result = $createUser
            ->handle(
                new CreateUserInput(
                    $request->phone_number,
                    $request->password
                )
            );

        return RegisterResponseData::fromCreateUserResult(
            $result
        );

    }
}
