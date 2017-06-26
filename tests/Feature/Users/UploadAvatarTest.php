<?php

namespace Tests\Feature\Users;

use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class UploadAvatarTest
 * @package Tests\Feature\Users
 */
class UploadAvatarTest extends FeatureTestCase
{
    /** @test */
    function upload_avatar_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->patchJson('api/v1/avatar')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function upload_avatar_without_image()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->patchJson('api/v1/avatar')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson(['avatar' => ['The avatar field is required.']]);
    }

    /** @test */
    function upload_avatar_with_invalid_image()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->patchJson('api/v1/avatar', ['avatar' => str_random()])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson(['avatar' => ['The avatar must be an image.']]);
    }

    /** @test */
    function upload_avatar()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->patchJson('api/v1/avatar', [
                'avatar' => UploadedFile::fake()->image('avatar.jpg')
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['avatar']);

        $avatar = $response->json()['avatar'];

        $this->assertEquals($avatar, $user->fresh()->getAvatar());

        $this->assertFileExists(storage_path('app/avatars/' . $avatar));
    }
}
