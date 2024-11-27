<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCaseApi;

class LoginTest extends TestCaseApi
{
    use RefreshDatabase;

    #[Test]
    public function a_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson(route("api.{$this->apiVersion}.auth.login"), [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
        $this->assertSuccessResponse($response, ['token', 'user']);
    }

    #[Test]
    public function a_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->postJson(route("api.{$this->apiVersion}.auth.login"), [
            'email' => 'invalid@example.com',
            'password' => 'invalid',
        ]);

        $this->assertErrorResponse($response, 422, ['email']);
    }
}
