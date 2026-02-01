<?php

namespace App\Data\User\Auth\Registeration\Register\Request;

use App\Models\User;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class RegisterRequestData extends Data
{
    public function __construct(
        #[
            OAT\Property,
            Unique(User::class, 'phone_number')
        ]
        public string $phone_number,
        #[OAT\Property]
        public string $password,
    ) {}
}
