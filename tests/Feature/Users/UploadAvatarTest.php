<?php

namespace Tests\Feature\Users;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
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
            ->assertJsonFragment(['The avatar must be an image.']);
    }

    /**
     * @param int $height
     * @param int $width
     */
    function upload_avatar_fails(int $height, int $width)
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson('api/v1/avatar', [
                'avatar' => UploadedFile::fake()->image('avatar.jpg', $height, $width)
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['avatar' => ['The avatar has invalid image dimensions.']]);
    }

    /** @test */
    function upload_avatar_with_invalid_ratio()
    {
        $this->upload_avatar_fails(200, 250);
    }

    /** @test */
    function upload_avatar_with_too_small_height()
    {
        $this->upload_avatar_fails(100, 250);
    }

    /** @test */
    function upload_avatar_with_too_small_width()
    {
        $this->upload_avatar_fails(250, 100);
    }

    /** @test */
    function upload_avatar_with_too_large_width()
    {
        $this->upload_avatar_fails(250, 2048);
    }

    /** @test */
    function upload_avatar_with_too_large_height()
    {
        $this->upload_avatar_fails(2048, 250);
    }

    /** @test */
    function upload_avatar()
    {
        $user = $this->createUser(['updated_at' => 1]);

        $response = $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->postJson('api/v1/avatar', [
                'avatar' => UploadedFile::fake()->image('avatar.jpg', 250, 250)
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['avatar']);

        $avatar = $response->json()['avatar'];

        $fresh = $user->fresh();

        $this->assertEquals($this->avatarUrl($avatar), $fresh->getAvatar());

        $updatedAt = $fresh->getUpdatedAt();
        $this->assertGreaterThan($user->getUpdatedAt(), $updatedAt);

        $path = storage_path('app/public/avatars/' . $avatar);
        $this->assertFileExists($path);

        /* @var ImageManager $imageManager */
        $imageManager = $this->app->make(ImageManager::class);
        $image = $imageManager->make($path);
        /**
         * Make sure the uploaded avatar is resized.
         */
        $this->assertEquals(128, $image->height());
        $this->assertEquals(128, $image->width());
    }
}
