<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCaseApi;

class UpdateProfileTest extends TestCaseApi
{
    use RefreshDatabase;

    #[Test]
    public function an_authenticated_user_can_update_their_profile()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->putJson(route("api.{$this->apiVersion}.profile.update"), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $this->assertSuccessResponse($response, ['user' => ['name', 'email']]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    #[Test]
    public function updating_profile_requires_valid_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->putJson(route("api.{$this->apiVersion}.profile.update"), [
            'name' => '',
            'email' => 'not-an-email',
        ]);

        $this->assertErrorResponse($response, 422, ['name', 'email']);
    }

    #[Test]
    public function an_unauthenticated_user_cannot_update_profile()
    {
        $response = $this->putJson(route("api.{$this->apiVersion}.profile.update"), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $this->assertErrorResponse($response, 401);
    }

    #[Test]
    public function updating_profile_with_existing_email_fails()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->actingAs($user, 'sanctum')->putJson(route("api.{$this->apiVersion}.profile.update"), [
            'name' => 'Updated Name',
            'email' => 'existing@example.com',
        ]);

        $this->assertErrorResponse($response, 422, ['email']);
    }
}
