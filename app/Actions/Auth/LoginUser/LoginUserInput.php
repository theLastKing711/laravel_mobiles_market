<?php

namespace App\Actions\Auth\LoginUser;

class LoginUserInput
{
    public function __construct(
        public string $phone_number,
        public string $password,
    ) {}

}
