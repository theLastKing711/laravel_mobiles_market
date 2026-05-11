<?php

namespace App\Http\Controllers\User\Auth;

use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OAT;

class DeleteAccountController extends Controller
{
    #[OAT\Delete(path: '/users/auth', tags: ['usersAuth'])]
    #[SuccessNoContentResponse]
    public function __invoke()
    {

        User::query()
            ->firstWhere(
                'id',
                Auth::User()->id
            )
            ->delete();
    }
}
