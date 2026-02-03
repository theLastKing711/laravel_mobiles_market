<?php

namespace App\Http\Controllers\User\Auth\Login;

use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\User\Auth\Login\AddPhoneNumberLoginStep\Request\AddPhoneNumberLoginStepRequestData;
use App\Data\User\Auth\Login\AddPhoneNumberLoginStep\Response\AddPhoneNumberLoginStepResponseData;
use App\Data\User\Auth\Registeration\AddPhoneNumberRegisterationStep\Request\AddPhoneNumberRegisterationStepRequestData;
use App\Http\Controllers\Controller;
use Cloudinary\Api\HttpStatusCode;
use OpenApi\Attributes as OAT;
use Tests\Feature\User\Auth\LoginTest;

class AddPhoneNumberLoginStepController extends Controller
{
    const TEST_CLASS = LoginTest::class;

    #[OAT\Post(path: '/users/auth/login/phone-number-step', tags: ['usersAuth'])]
    #[JsonRequestBody(AddPhoneNumberRegisterationStepRequestData::class)]
    #[SuccessItemResponse(AddPhoneNumberLoginStepResponseData::class)]
    public function __invoke(AddPhoneNumberLoginStepRequestData $request)
    {

        return response(
            [
                'message' => 'Success',
            ],
            HttpStatusCode::OK
        );

    }
}
