<?php

namespace Tests\Feature\Users;

use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class RegisterUserTest
 * @package Tests\Feature\Users
 */
class RegisterUserTest extends FeatureTestCase
{
    /** @test */
    function register_user_without_name()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['name' => ['The name field is required.']]);
    }

    /** @test */
    function register_user_with_too_long_name()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users', ['name' => str_random(256)])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['name' => ['The name may not be greater than 255 characters.']]);
    }

    /** @test */
    function register_user_without_email()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['email' => ['The email field is required.']]);
    }

    /** @test */
    function register_user_with_invalid_email()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users', ['email' => str_random()])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['email' => ['The email must be a valid email address.']]);
    }

    /** @test */
    function register_user_with_taken_email()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users', ['email' => $this->createUser()->getAttribute('email')])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['email' => ['The email has already been taken.']]);
    }

    /** @test */
    function register_user_without_password()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['password' => ['The password field is required.']]);
    }

    /** @test */
    function register_user_with_too_long_password()
    {
        $password = str_random(256);

        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users', ['password' => $password, 'password_confirmation' => $password])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['password' => ['The password may not be greater than 255 characters.']]);
    }

    /** @test */
    function register_user_without_password_confirmation()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users', ['password' => str_random()])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['password' => ['The password confirmation does not match.']]);
    }

    /** @test */
    function register_user_with_too_small_password()
    {
        $password = '12345';

        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users', ['password' => $password, 'password_confirmation' => $password])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['password' => ['The password must be at least 6 characters.']]);
    }

    /** @test */
    function register_user()
    {
        $random = str_random();

        $name = ['name' => $random];

        $password = ['password' => $random];

        $email = ['email' => $random . '@mail.com'];

        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users', $email + $name + $password + ['password_confirmation' => $random])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['name'])
            ->assertJson($name)
            ->assertJsonMissing(['id', 'email', 'password', 'created_at', 'updated_at']);

        $this->assertDatabaseHas('users', $name + $email);

        /**
         * Make sure the password is encrypted.
         */
        $this->assertDatabaseMissing('users', $password);
    }
}
