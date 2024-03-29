<?php

namespace Tests\Features\Users;

use Symfony\Component\HttpFoundation\Response;
use Tests\Features\FeatureTestCase;

/**
 * Class RegisterUserTest
 * @package Tests\Features\Users
 */
class RegisterUserTest extends FeatureTestCase
{
    /** @test */
    function register_user_without_json_format()
    {
        $this->assertGuest('api')
            ->post('api/v1/users')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function register_user_without_name()
    {
        $this->assertGuest('api')
            ->postJson('api/v1/users')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['name' => ['The name field is required.']]);
    }

    /** @test */
    function register_user_with_too_long_name()
    {
        $this->assertGuest('api')
            ->postJson('api/v1/users', ['name' => str_random(256)])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['name' => ['The name may not be greater than 255 characters.']]);
    }

    /** @test */
    function register_user_without_email()
    {
        $this->assertGuest('api')
            ->postJson('api/v1/users')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['email' => ['The email field is required.']]);
    }

    /** @test */
    function register_user_with_invalid_email()
    {
        $this->assertGuest('api')
            ->postJson('api/v1/users', ['email' => str_random()])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['email' => ['The email must be a valid email address.']]);
    }

    /** @test */
    function register_user_with_taken_email()
    {
        $this->assertGuest('api')
            ->postJson('api/v1/users', ['email' => $this->createUser()->getAttribute('email')])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['email' => ['The email has already been taken.']]);
    }

    /** @test */
    function register_user_without_password()
    {
        $this->assertGuest('api')
            ->postJson('api/v1/users')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['password' => ['The password field is required.']]);
    }

    /** @test */
    function register_user_with_too_long_password()
    {
        $password = str_random(256);

        $this->assertGuest('api')
            ->postJson('api/v1/users', ['password' => $password, 'password_confirmation' => $password])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['password' => ['The password may not be greater than 255 characters.']]);
    }

    /** @test */
    function register_user_without_password_confirmation()
    {
        $this->assertGuest('api')
            ->postJson('api/v1/users', ['password' => str_random()])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['password' => ['The password confirmation does not match.']]);
    }

    /** @test */
    function register_user_with_too_small_password()
    {
        $password = '12345';

        $this->assertGuest('api')
            ->postJson('api/v1/users', ['password' => $password, 'password_confirmation' => $password])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['password' => ['The password must be at least 6 characters.']]);
    }

    /** @test */
    function register_user_without_username()
    {
        $this->assertGuest('api')
            ->postJson('api/v1/users')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['username' => ['The username field is required.']]);
    }

    /** @test */
    function register_user_with_taken_username()
    {
        $this->assertGuest('api')
            ->postJson('api/v1/users', ['username' => $this->createUser()->getUsername()])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['username' => ['The username has already been taken.']]);
    }

    /** @test */
    function register_user_with_too_long_username()
    {
        $this->assertGuest('api')
            ->postJson('api/v1/users', ['username' => str_random(256)])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['username' => ['The username may not be greater than 255 characters.']]);
    }

    /** @test */
    function register_user()
    {
        [$name, $username, $password, $email] = [str_random(), str_random(), str_random(), str_random() . '@mail.com'];

        $this->assertGuest('api')
            ->postJson('api/v1/users', [
                'name' => $name,
                'username' => $username,
                'password' => $password,
                'email' => $email,
                'password_confirmation' => $password
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->userJsonStructure())
            ->assertExactJson([
                'name' => $name,
                'username' => $username,
                'avatar' => '/static/avatar.png'
            ])
            ->assertJsonMissing(['id', 'email', 'password', 'created_at', 'updated_at']);

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'username' => $username,
            'email' => $email
        ]);

        /**
         * Make sure the password is encrypted.
         */
        $this->assertDatabaseMissing('users', [
            'password' => $password
        ]);
    }
}
