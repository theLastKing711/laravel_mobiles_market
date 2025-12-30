<?php

namespace App\Http\Controllers\User\Auth;

use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\User\Auth\GetUserPhoneNumber\Response\GetUserPhoneNumberResponseData;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OAT;

class GetUserPhoneNumberController extends Controller
{
    #[OAT\Get(path: '/users/auth/get-user-phone-number', tags: ['usersAuth'])]
    #[SuccessItemResponse(GetUserPhoneNumberResponseData::class)]
    public function __invoke()
    {

        $logged_user_id = Auth::User()->id;

        return GetUserPhoneNumberResponseData::from(
            User::query()
                ->firstWhere(
                    'id',
                    $logged_user_id
                )
        );
    }
}
