<?php

namespace App\Data\User\Auth\Login\Login\Request;

use App\Models\User;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class LoginRequestData extends Data
{
    public function __construct(
        #[
            OAT\Property,
            Exists(User::class, 'phone_number')
        ]
        public string $phone_number,
        #[OAT\Property]
        public string $password,
    ) {}

}
