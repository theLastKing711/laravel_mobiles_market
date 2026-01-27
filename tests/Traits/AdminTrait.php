<?php

namespace Tests\Traits;

use App\Models\User;

trait AdminTrait
{
    public User $admin;

    public function getAdmin(): User
    {
        return
            User::factory()
                ->admin()
                ->create();

    }

    public function initializeAdmin()
    {

        $this->admin =
           $this
               ->getAdmin();

        $this->actingAs($this->admin);
    }
}
