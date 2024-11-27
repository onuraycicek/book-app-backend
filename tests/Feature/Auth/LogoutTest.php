<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCaseApi;

class LogoutTest extends TestCaseApi
{
    use RefreshDatabase;

    #[Test]
    public function a_logged_in_user_can_logout()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson(route("api.{$this->apiVersion}.auth.logout"));

        $this->assertSuccessResponse($response);
    }
}
