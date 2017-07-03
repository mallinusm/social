<?php

namespace Tests\Feature\Users;

use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class FetchAvatarTest
 * @package Tests\Feature\Users
 */
class FetchAvatarTest extends FeatureTestCase
{
    /** @test */
    function fetch_unknown_avatar()
    {
        $random = str_random();

        $this->dontSeeIsAuthenticated('api')
            ->getJson("cdn/avatars/{$random}")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson(['error' => 'The avatar does not exist.']);
    }

    /** @test */
    function fetch_avatar()
    {
        UploadedFile::fake()->image($name = 'avatar.jpg')->move(storage_path('app/public/avatars'), $name);

        $this->dontSeeIsAuthenticated('api')
            ->getJson("cdn/avatars/{$name}")
            ->assertStatus(Response::HTTP_OK);
    }
}
