<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCaseApi;

class ResetPasswordTest extends TestCaseApi
{
    use RefreshDatabase;

    #[Test]
    public function a_user_can_reset_their_password()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);
        $token = Password::createToken($user);

        $response = $this->postJson(route("api.{$this->apiVersion}.password.reset"), [
            'email' => 'test@example.com',
            'token' => $token,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $this->assertSuccessResponse($response);
    }
}
