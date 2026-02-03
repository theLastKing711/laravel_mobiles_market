<?php

namespace App\Http\Controllers\User\Auth\Registeration;

use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\User\Auth\Registeration\AddPhoneNumberRegisterationStep\Request\AddPhoneNumberRegisterationStepRequestData;
use App\Data\User\Auth\Registeration\AddPhoneNumberRegisterationStep\Response\AddPhoneNumberRegisterationStepResponseData;
use App\Http\Controllers\Controller;
use Cloudinary\Api\HttpStatusCode;
use OpenApi\Attributes as OAT;
use Tests\Feature\User\Auth\RegisterationTest;

class AddPhoneNumberRegisterationStepController extends Controller
{
    const TEST_CLASS = RegisterationTest::class;

    #[OAT\Post(path: '/users/auth/registeration/phone-number-step', tags: ['usersAuth'])]
    #[JsonRequestBody(AddPhoneNumberRegisterationStepRequestData::class)]
    #[SuccessItemResponse(AddPhoneNumberRegisterationStepResponseData::class)]
    public function __invoke(AddPhoneNumberRegisterationStepRequestData $request)
    {
        return response(
            [
                'message' => 'Success',
            ],
            HttpStatusCode::OK
        );

    }
}
