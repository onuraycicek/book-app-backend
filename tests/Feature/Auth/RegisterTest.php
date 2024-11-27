<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCaseApi;

class RegisterTest extends TestCaseApi
{
    use RefreshDatabase;

    #[Test]
    public function a_user_can_register()
    {
        $response = $this->postJson(route("api.{$this->apiVersion}.auth.register"), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertSuccessResponse($response, ['token', 'user']);
    }

    #[Test]
    public function a_user_cannot_register_with_invalid_data()
    {
        $response = $this->postJson(route("api.{$this->apiVersion}.auth.register"), [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'short',
            'password_confirmation' => 'different',
        ]);

        $this->assertErrorResponse($response, 422, ['name', 'email', 'password']);
    }
}
