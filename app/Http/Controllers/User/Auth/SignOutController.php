<?php

namespace App\Http\Controllers\User\Auth;

use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OAT;
use Illuminate\Http\Request;

class SignOutController extends Controller
{
    #[OAT\Patch(path: '/users/auth/sign-out', tags: ['usersAuth'])]
    #[SuccessNoContentResponse]
    public function __invoke(Request $request)
    {
        Auth::user()->tokens()->delete(); // Log the user out of the application

        $request->session()->invalidate(); // Clear the session data

        $request->session()->regenerateToken(); // Regenerate the CSRF token
    }
}
