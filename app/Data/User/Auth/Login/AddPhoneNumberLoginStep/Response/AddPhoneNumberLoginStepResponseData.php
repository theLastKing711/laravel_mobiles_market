<?php

namespace App\Data\User\Auth\Login\AddPhoneNumberLoginStep\Response;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class AddPhoneNumberLoginStepResponseData extends Data
{
    public function __construct(
        #[OAT\Property]
        public string $token,
    ) {}
}
