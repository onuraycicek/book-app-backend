<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCaseApi;

class UpdatePictureTest extends TestCaseApi
{
    use RefreshDatabase;

    protected string $apiVersion;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiVersion = config('api.default_version');
        Storage::fake('public');
    }

    #[Test]
    public function an_authenticated_user_can_update_their_profile_picture()
    {
        $user = User::factory()->create();

        $file = UploadedFile::fake()->image('avatar.jpg');
        $expectedFileName = "{$user->id}.{$file->extension()}";
        $expectedFilePath = "user/profile_pictures/{$expectedFileName}";

        $response = $this->actingAs($user)->putJson(route("api.{$this->apiVersion}.profile.picture.update"), [
            'picture' => $file,
        ]);

        $this->assertSuccessResponse($response, ['picture_url'], ['picture_url' => Storage::url($expectedFilePath)]);

        Storage::disk('public')->assertExists($expectedFilePath);

        $this->assertEquals($expectedFilePath, $user->fresh()->picture);
    }

    #[Test]
    public function updating_picture_requires_an_image_file()
    {
        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAs($user)->putJson(route("api.{$this->apiVersion}.profile.picture.update"), [
            'picture' => $file,
        ]);

        $this->assertErrorResponse($response, 422, ['picture']);
    }

    #[Test]
    public function updating_picture_with_large_file_fails()
    {
        $user = User::factory()->create();

        $file = UploadedFile::fake()->image('avatar.jpg')->size(3000);

        $response = $this->actingAs($user)->putJson(route("api.{$this->apiVersion}.profile.picture.update"), [
            'picture' => $file,
        ]);

        $this->assertErrorResponse($response, 422, ['picture']);
    }

    #[Test]
    public function an_unauthenticated_user_cannot_update_profile_picture()
    {
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->putJson(route("api.{$this->apiVersion}.profile.picture.update"), [
            'picture' => $file,
        ]);

        $this->assertErrorResponse($response, 401);
    }

    #[Test]
    public function updating_picture_replaces_old_picture()
    {
        $user = User::factory()->create([
            'picture' => 'user/profile_pictures/old_avatar.jpg',
        ]);

        Storage::disk('public')->put('user/profile_pictures/old_avatar.jpg', 'old content');

        $newFile = UploadedFile::fake()->image('new_avatar.jpg');
        $expectedNewFileName = "{$user->id}.{$newFile->extension()}";
        $expectedNewFilePath = "user/profile_pictures/{$expectedNewFileName}";

        $response = $this->actingAs($user)->putJson(route("api.{$this->apiVersion}.profile.picture.update"), [
            'picture' => $newFile,
        ]);

        $this->assertSuccessResponse($response, ['picture_url'], ['picture_url' => Storage::url($expectedNewFilePath)]);

        Storage::disk('public')->assertMissing('user/profile_pictures/old_avatar.jpg');

        Storage::disk('public')->assertExists($expectedNewFilePath);

        $this->assertEquals($expectedNewFilePath, $user->fresh()->picture);
    }
}
