<?php

namespace App\Data\User\Auth\Registeration\Register\Request;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class RegisterRequestData extends Data
{
    public function __construct(
        // #[Unique('users', 'phone_number')]
        #[OAT\Property]
        public string $phone_number,
        #[OAT\Property]
        public string $password,
        // #[OAT\Property]
        // public string $fcm_token,
    ) {}
}
