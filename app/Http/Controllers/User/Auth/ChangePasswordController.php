<?php

namespace App\Http\Controllers\User\Auth;

use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Data\User\Auth\ChangePassword\Request\ChangePasswordRequestData;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OAT;

class ChangePasswordController extends Controller
{
    #[OAT\Patch(path: '/users/auth/change-password', tags: ['usersAuth'])]
    #[JsonRequestBody(ChangePasswordRequestData::class)]
    #[SuccessNoContentResponse]
    public function __invoke(ChangePasswordRequestData $changePasswordRequestData)
    {

        $logged_user_id = Auth::User()->id;

        User::query()
            ->firstWhere(
                'id',
                $logged_user_id
            )
            ->update([
                'password' => $changePasswordRequestData->password,
            ]);

    }
}
