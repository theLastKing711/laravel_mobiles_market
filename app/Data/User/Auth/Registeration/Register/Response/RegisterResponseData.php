<?php

namespace App\Data\User\Auth\Registeration\Register\Response;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class RegisterResponseData extends Data
{
    public function __construct(
        #[OAT\Property]
        public string $token,
        #[OAT\Property]
        public string $role,
    ) {}
}
