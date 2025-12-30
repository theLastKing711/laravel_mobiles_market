<?php

namespace App\Data\User\Auth\Login\Login\Request;

use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class LoginRequestData extends Data
{
    public function __construct(
        #[OAT\Property]
        public string $phone_number,
        #[OAT\Property]
        public string $password,
    ) {}

    // public static function rules(ValidationContext $context): array
    // {

    //     $phone_number = $context->payload['phone_number'];
    //     $password = $context->payload['password'];

    //     if(!Auth::attempt(['phone_number' => $phone_number, 'password' => $password]))
    //     {

    //     }

    //     return [
    //         'id' => 'required',
    //     ];
    // }

}
