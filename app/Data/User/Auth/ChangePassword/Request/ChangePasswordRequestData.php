<?php

namespace App\Data\User\Auth\ChangePassword\Request;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class ChangePasswordRequestData extends Data
{
    public function __construct(
        #[OAT\Property]
        public string $password,
    ) {}
}
