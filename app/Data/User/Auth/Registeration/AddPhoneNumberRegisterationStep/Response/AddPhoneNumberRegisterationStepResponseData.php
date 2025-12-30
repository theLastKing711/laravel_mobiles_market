<?php

namespace App\Data\User\Auth\Registeration\AddPhoneNumberRegisterationStep\Response;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class AddPhoneNumberRegisterationStepResponseData extends Data
{
    public function __construct(
        #[OAT\Property]
        public string $token,
    ) {}
}
