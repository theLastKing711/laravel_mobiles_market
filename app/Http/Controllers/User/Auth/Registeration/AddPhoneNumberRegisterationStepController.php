<?php

namespace App\Http\Controllers\User\Auth\Registeration;

use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\User\Auth\Registeration\AddPhoneNumberRegisterationStep\Request\AddPhoneNumberRegisterationStepRequestData;
use App\Data\User\Auth\Registeration\AddPhoneNumberRegisterationStep\Response\AddPhoneNumberRegisterationStepResponseData;
use App\Http\Controllers\Controller;
use App\Models\User;
use Cloudinary\Api\HttpStatusCode;
use OpenApi\Attributes as OAT;

class AddPhoneNumberRegisterationStepController extends Controller
{
    #[OAT\Post(path: '/users/auth/registeration/phone-number-step', tags: ['usersAuth'])]
    #[JsonRequestBody(AddPhoneNumberRegisterationStepRequestData::class)]
    #[SuccessItemResponse(AddPhoneNumberRegisterationStepResponseData::class)]
    public function __invoke(AddPhoneNumberRegisterationStepRequestData $request)
    {

        $request_phone_number = $request->phone_number;

        $is_phone_number_duplicated =
                User::query()
                    ->where('phone_number', $request_phone_number)
                    ->exists();

        if ($is_phone_number_duplicated) {
            return response(
                [
                    'message' => 'لديك حساب مسجل مسبقا. سجل الدخول بالرقم المدخل ؟',
                    'errors' => [
                        'phone_number' => [
                            'لديك حساب مسجل مسبقا. سجل الدخول بالرقم المدخل ؟',
                        ],
                    ],
                ],
                HttpStatusCode::CONFLICT
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
