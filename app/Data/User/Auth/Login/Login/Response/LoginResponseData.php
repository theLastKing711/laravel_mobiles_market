<?php

namespace App\Data\User\Auth\Login\Login\Response;

use App\Actions\Auth\LoginUser\LoginUserResult;
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

    public static function fromLoginUserResult(LoginUserResult $loginUserResult): self
    {
        return new self(
            $loginUserResult->token,
            $loginUserResult->role
        );
    }
}
