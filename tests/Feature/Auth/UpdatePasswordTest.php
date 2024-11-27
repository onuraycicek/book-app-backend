<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCaseApi;

class UpdatePasswordTest extends TestCaseApi
{
    use RefreshDatabase;

    #[Test]
    public function a_user_can_update_their_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('oldpassword'),
        ]);

        $this->actingAs($user);

        $response = $this->putJson(route("api.{$this->apiVersion}.password.update"), [
            'current_password' => 'oldpassword',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword',
        ]);

        $this->assertSuccessResponse($response);
    }

    #[Test]
    public function a_user_cannot_update_password_with_incorrect_current_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('oldpassword'),
        ]);

        $this->actingAs($user);

        $response = $this->putJson(route("api.{$this->apiVersion}.password.update"), [
            'current_password' => 'wrongpassword',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword',
        ]);

        $this->assertErrorResponse($response, 422, ['current_password']);
    }
}
