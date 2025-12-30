<?php

namespace App\Http\Controllers\User\Auth\Login;

use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\User\Auth\Login\AddPhoneNumberLoginStep\Response\AddPhoneNumberLoginStepResponseData;
use App\Data\User\Auth\Registeration\AddPhoneNumberRegisterationStep\Request\AddPhoneNumberRegisterationStepRequestData;
use App\Http\Controllers\Controller;
use App\Models\User;
use Cloudinary\Api\HttpStatusCode;
use OpenApi\Attributes as OAT;

class AddPhoneNumberLoginStepController extends Controller
{
    #[OAT\Post(path: '/users/auth/login/phone-number-step', tags: ['usersAuth'])]
    #[JsonRequestBody(AddPhoneNumberRegisterationStepRequestData::class)]
    #[SuccessItemResponse(AddPhoneNumberLoginStepResponseData::class)]
    public function __invoke(AddPhoneNumberRegisterationStepRequestData $request)
    {

        $request_phone_number = $request->phone_number;

        $is_phone_number_registered =
                User::where('phone_number', $request_phone_number)
                    ->exists();

        if (! $is_phone_number_registered) {
            return response(
                [
                    'message' => 'الرقم المدخل لا يوجد حساب له.',
                    'errors' => [
                        'phone_number' => [
                            'الرقم المدخل لا يوجد حساب له.',
                        ],
                    ],
                ],
                HttpStatusCode::NOT_FOUND
            );
        }

        return response(
            [
                'message' => 'Success',
            ],
            HttpStatusCode::OK
        );

    }
}
