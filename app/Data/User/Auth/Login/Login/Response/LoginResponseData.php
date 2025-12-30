<?php

namespace App\Data\User\Auth\Login\Login\Response;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class LoginResponseData extends Data
{
    public function __construct(
        #[OAT\Property]
        public string $token,
        #[OAT\Property]
        public string $role,
    ) {}
}
