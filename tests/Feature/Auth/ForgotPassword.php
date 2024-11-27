<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCaseApi;

class ForgotPasswordTest extends TestCaseApi
{
    use RefreshDatabase;

    #[Test]
    public function a_user_can_request_password_reset_link()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->postJson(route("api.{$this->apiVersion}.password.email"), [
            'email' => 'test@example.com',
        ]);

        $this->assertSuccessResponse($response);
    }
}
