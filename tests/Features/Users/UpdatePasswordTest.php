<?php

namespace Tests\Features\Users;

use Illuminate\Contracts\Hashing\Hasher;
use Symfony\Component\HttpFoundation\Response;
use Tests\Features\FeatureTestCase;

/**
 * Class UpdatePasswordTest
 * @package Tests\Features\Users
 */
class UpdatePasswordTest extends FeatureTestCase
{
    /** @test */
    function update_password_without_json_format()
    {
        $this->assertGuest('api')
            ->patch('api/v1/password')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function update_password_without_authentication()
    {
        $this->assertGuest('api')
            ->patchJson('api/v1/password')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    /** @test */
    function update_password_without_old_password()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->patchJson('api/v1/password')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['old_password' => ['The old password field is required.']]);
    }

    /** @test */
    function update_password_with_too_short_old_password()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->patchJson('api/v1/password', ['old_password' => str_random(5)])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['old_password' => ['The old password must be at least 6 characters.']]);
    }

    /** @test */
    function update_password_with_too_long_old_password()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->patchJson('api/v1/password', ['old_password' => str_random(256)])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['old_password' => ['The old password may not be greater than 255 characters.']]);
    }

    /** @test */
    function update_password_without_password()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->patchJson('api/v1/password')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['password' => ['The password field is required.']]);
    }

    /** @test */
    function update_password_with_too_short_password()
    {
        $password = str_random(5);

        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->patchJson('api/v1/password', [
                'password' => $password,
                'password_confirmation' => $password
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['password' => ['The password must be at least 6 characters.']]);
    }

    /** @test */
    function update_password_with_too_long_password()
    {
        $password = str_random(256);

        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->patchJson('api/v1/password', [
                'password' => $password,
                'password_confirmation' => $password
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['password' => ['The password may not be greater than 255 characters.']]);
    }

    /** @test */
    function update_password_with_invalid_old_password()
    {
        $password = str_random();

        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->patchJson('api/v1/password', [
                'old_password' => str_random(),
                'password' => $password,
                'password_confirmation' => $password
            ])
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment(['error' => 'Invalid password.']);
    }

    /** @test */
    function update_password()
    {
        $oldPassword = str_random();
        $password = str_random();

        $user = $this->createUser(['password' => bcrypt($oldPassword)]);

        $this->actingAs($user, 'api')
            ->assertAuthenticated('api')
            ->patchJson('api/v1/password', [
                'old_password' => $oldPassword,
                'password' => $password,
                'password_confirmation' => $password
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['message' => 'Password updated.']);

        /**
         * Make sure the password is encrypted.
         */
        $this->assertDatabaseMissing('users', [
            'password' => $password
        ]);

        /* @var Hasher $hasher */
        $hasher = $this->app->make(Hasher::class);
        $this->assertTrue($hasher->check($password, $user->fresh()->getPassword()));
    }
}
