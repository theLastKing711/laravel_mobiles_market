<?php

namespace App\Data\User\Auth\Login\Login\Request;

use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class LoginRequestData extends Data
{
    public static function authorize(): bool
    {

        $phone_number =
            request('phone_number');

        $password =
            request('password');

        $is_user_data_valid =
            Auth::attempt(
                [
                    'phone_number' => $phone_number,
                    'password' => $password,
                ]
            );

        if (! $is_user_data_valid) {
            return false;
        }

        return true;

    }

    public function __construct(
        #[OAT\Property]
        public string $phone_number,
        #[OAT\Property]
        public string $password,
    ) {}

}
