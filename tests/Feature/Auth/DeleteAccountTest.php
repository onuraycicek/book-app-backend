<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCaseApi;

class DeleteAccountTest extends TestCaseApi
{
    use RefreshDatabase;

    #[Test]
    public function a_logged_in_user_can_delete_their_account()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->deleteJson(route("api.{$this->apiVersion}.profile.delete"));

        $this->assertSuccessResponse($response);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    #[Test]
    public function a_guest_user_cannot_delete_an_account()
    {
        $response = $this->deleteJson(route("api.{$this->apiVersion}.profile.delete"));
        $this->assertErrorResponse($response, 401);
    }
}
