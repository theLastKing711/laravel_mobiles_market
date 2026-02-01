<?php

namespace App\Actions\Auth\CreateUser;

class CreateUserResult
{
    public function __construct(
        public string $token,
        public string $role,
    ) {}

}
