<?php

namespace App\Http\Controllers\User\Auth;

use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Data\User\Auth\ChangePhoneNumber\Request\ChangePhoneNumberRequestData;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OAT;

class ChangePhoneNumberController extends Controller
{
    #[OAT\Patch(path: '/users/auth/change-phone-number', tags: ['usersAuth'])]
    #[JsonRequestBody(ChangePhoneNumberRequestData::class)]
    #[SuccessNoContentResponse]
    public function __invoke(ChangePhoneNumberRequestData $changephoneNumberRequestData)
    {

        User::query()
            ->firstWhere(
                'id',
                Auth::User()->id
            )
            ->update([
                'phone_number' => $changephoneNumberRequestData->phone_number,
            ]);
    }
}
