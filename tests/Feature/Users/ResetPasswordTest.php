<?php

namespace Tests\Feature\Users;

use Illuminate\Contracts\Hashing\Hasher;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class ResetPasswordTest
 * @package Tests\Feature\Users
 */
class ResetPasswordTest extends FeatureTestCase
{
    /** @test */
    function reset_password_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->post('api/v1/reset-password')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function reset_password_without_token()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/reset-password')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['token' => ['The token field is required.']]);
    }

    /** @test */
    function reset_password_with_too_short_token()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/reset-password', ['token' => str_random()])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['token' => ['The token must be at least 100 characters.']]);
    }

    /** @test */
    function reset_password_with_too_long_token()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/reset-password', ['token' => str_random(101)])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['token' => ['The token may not be greater than 100 characters.']]);
    }

    /** @test */
    function reset_password_with_non_existing_token()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/reset-password', ['token' => str_random(100)])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['token' => ['The selected token is invalid.']]);
    }

    /** @test */
    function reset_password_without_password()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/reset-password')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['password' => ['The password field is required.']]);
    }

    /** @test */
    function reset_password_with_too_short_password()
    {
        $random = str_random(5);

        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/reset-password', [
                'password' => $random,
                'password_confirmation' => $random
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['password' => ['The password must be at least 6 characters.']]);
    }

    /** @test */
    function reset_password_with_too_long_password()
    {
        $random = str_random(256);

        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/reset-password', [
                'password' => $random,
                'password_confirmation' => $random
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['password' => ['The password may not be greater than 255 characters.']]);
    }

    /** @test */
    function reset_password_without_password_confirmation()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/reset-password', ['password' => str_random()])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['password' => ['The password confirmation does not match.']]);
    }

    /** @test */
    function reset_password_with_expired_token()
    {
        $password = str_random();

        $moreThanOneHourAgo = time() - (60 * 60) - 1;

        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/reset-password', [
                'token' => $this->createPasswordReset(['created_at' => $moreThanOneHourAgo])->getToken(),
                'password' => $password,
                'password_confirmation' => $password
            ])
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment(['error' => 'This action is unauthorized.']);
    }

    /** @test */
    function reset_password()
    {
        $user = $this->createUser();
        $email = $user->getEmail();

        $passwordReset = $this->createPasswordReset(['email' => $email]);
        $token =  $passwordReset->getToken();

        $password = str_random();

        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/reset-password', [
                'token' => $token,
                'password' => $password,
                'password_confirmation' => $password
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['message' => 'Password was reset.']);

        $this->assertDatabaseMissing('password_resets', [
            'email' => $email,
            'token' => $token
        ]);

        /**
         * Make sure the password was encrypted.
         */
        $this->assertDatabaseMissing('users', [
            'password' => $password
        ]);

        /* @var Hasher $hasher */
        $hasher = $this->app->make(Hasher::class);
        $this->assertTrue($hasher->check($password, $user->fresh()->getPassword()));
    }
}
