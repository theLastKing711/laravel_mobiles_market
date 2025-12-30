<?php

namespace App\Http\Controllers\User\Auth;

use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Data\User\Auth\ChangePhoneNumber\Request\ChangePhoneNumberRequestData;
use App\Http\Controllers\Controller;
use App\Models\User;
use Cloudinary\Api\HttpStatusCode;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OAT;

class ChangePhoneNumberController extends Controller
{
    #[OAT\Patch(path: '/users/auth/change-phone-number', tags: ['usersAuth'])]
    #[JsonRequestBody(ChangePhoneNumberRequestData::class)]
    #[SuccessNoContentResponse]
    public function __invoke(ChangePhoneNumberRequestData $changephoneNumberRequestData)
    {
        $logged_user_id = Auth::User()->id;

        $phone_number_is_already_taken =
            User::query()
                ->where('phone_number', $changephoneNumberRequestData->phone_number)
                ->exists();

        if ($phone_number_is_already_taken) {
            return response([
                'message' => 'رقم الهاتف مفعل مسبقا, يرجى إدخال رقم جديد',
            ],
                HttpStatusCode::CONFLICT);
        }

        User::query()
            ->firstWhere(
                'id',
                $logged_user_id
            )
            ->update([
                'phone_number' => $changephoneNumberRequestData->phone_number,
            ]);
    }
}
