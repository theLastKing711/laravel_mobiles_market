<?php

namespace Tests\Traits;

use App\Models\User;

trait StoreTrait
{
    public User $store;

    public function getStore(): User
    {
        return
            User::factory()
                ->storeUser()
                ->create();

    }

    public function initializeStore()
    {

        $this->store =
           $this
               ->getStore();

        $this->actingAs($this->store);
    }
}
