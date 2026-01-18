<?php

namespace Tests\Feature\User\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterationsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function reandom_test(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
