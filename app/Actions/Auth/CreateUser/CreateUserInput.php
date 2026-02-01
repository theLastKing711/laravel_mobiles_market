<?php

namespace App\Actions\Auth\CreateUser;

class CreateUserInput
{
    public function __construct(
        public string $phone_number,
        public string $password,
    ) {}

}
