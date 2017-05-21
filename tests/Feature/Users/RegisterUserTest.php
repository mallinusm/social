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
    /**
     * @return void
     */
    public function testCannotRegisterUserWithoutName(): void
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['name' => ['The name field is required.']]);
    }

    /**
     * @return void
     */
    public function testCannotRegisterUserWithTooLongName(): void
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users', ['name' => str_random(256)])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['name' => ['The name may not be greater than 255 characters.']]);
    }

    /**
     * @return void
     */
    public function testCannotRegisterUserWithoutEmail(): void
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['email' => ['The email field is required.']]);
    }

    /**
     * @return void
     */
    public function testCannotRegisterUserWithInvalidEmail(): void
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users', ['email' => str_random()])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['email' => ['The email must be a valid email address.']]);
    }

    /**
     * @return void
     */
    public function testCannotRegisterUserWithTakenEmail(): void
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users', ['email' => $this->createUser()->getAttribute('email')])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['email' => ['The email has already been taken.']]);
    }

    /**
     * @return void
     */
    public function testCannotRegisterUserWithoutPassword(): void
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['password' => ['The password field is required.']]);
    }

    /**
     * @return void
     */
    public function testCannotRegisterUserWithoutPasswordConfirmation(): void
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users', ['password' => str_random()])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['password' => ['The password confirmation does not match.']]);
    }

    /**
     * @return void
     */
    public function testCannotRegisterUserWithTooSmallPassword(): void
    {
        $password = '12345';

        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users', ['password' => $password, 'password_confirmation' => $password])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['password' => ['The password must be at least 6 characters.']]);
    }

    /**
     * @return void
     */
    public function testCanRegisterUser(): void
    {
        $visible = [
            'name' => str_random(),
            'email' => str_random() . '@mail.com'
        ];

        $hidden = [
            'password' => $password = str_random()
        ];

        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users', $visible + $hidden + ['password_confirmation' => $password])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment($visible)
            ->assertJsonMissing($hidden)
            ->assertJsonStructure(['id', 'name', 'email', 'created_at', 'updated_at']);

        $this->assertDatabaseHas('users', $visible);

        /**
         * Make sure the password is encrypted.
         */
        $this->assertDatabaseMissing('users', $hidden);
    }
}