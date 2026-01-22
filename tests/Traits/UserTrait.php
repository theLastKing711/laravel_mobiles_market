<?php

namespace Tests\Traits;

use App\Models\User;

trait UserTrait
{
    public User $user;

    public function getUser(): User
    {
        return
            User::factory()
                ->user()
                ->create();

    }

    public function initializeUser()
    {

        $this->user =
           $this
               ->getUser();

        $this->actingAs($this->user);
    }
}
