<?php

namespace App\Actions\Auth\LoginUser;

class LoginUserResult
{
    public function __construct(
        public string $token,
        public string $role,
    ) {}

}
