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
    function upload_avatar_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->post('api/v1/avatar')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function upload_avatar_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/avatar')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function upload_avatar_without_image()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson('api/v1/avatar')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson(['avatar' => ['The avatar field is required.']]);
    }

    /** @test */
    function upload_avatar_with_invalid_image()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson('api/v1/avatar', ['avatar' => str_random()])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson(['avatar' => ['The avatar must be an image.']]);
    }

    /** @test */
    function upload_avatar()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->postJson('api/v1/avatar', [
                'avatar' => UploadedFile::fake()->image('avatar.jpg')
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['avatar']);

        $avatar = $response->json()['avatar'];

        $this->assertEquals($this->avatarUrl($avatar), $user->fresh()->getAvatar());

        $this->assertFileExists(storage_path('app/public/avatars/' . $avatar));
    }
}
