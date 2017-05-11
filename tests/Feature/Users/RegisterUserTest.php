<?php

namespace Tests\Feature\Users;

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
        $this->postJson('api/v1/users')
            ->assertStatus(422)
            ->assertJsonFragment(['name' => ['The name field is required.']]);
    }

    /**
     * @return void
     */
    public function testCannotRegisterUserWithTooLongName(): void
    {
        $this->postJson('api/v1/users', ['name' => str_random(256)])
            ->assertStatus(422)
            ->assertJsonFragment(['name' => ['The name may not be greater than 255 characters.']]);
    }

    /**
     * @return void
     */
    public function testCannotRegisterUserWithoutEmail(): void
    {
        $this->postJson('api/v1/users')
            ->assertStatus(422)
            ->assertJsonFragment(['email' => ['The email field is required.']]);
    }

    /**
     * @return void
     */
    public function testCannotRegisterUserWithInvalidEmail(): void
    {
        $this->postJson('api/v1/users', ['email' => str_random()])
            ->assertStatus(422)
            ->assertJsonFragment(['email' => ['The email must be a valid email address.']]);
    }

    /**
     * @return void
     */
    public function testCannotRegisterUserWithTakenEmail(): void
    {
        $this->postJson('api/v1/users', ['email' => $this->createUser()->getAttribute('email')])
            ->assertStatus(422)
            ->assertJsonFragment(['email' => ['The email has already been taken.']]);
    }

    /**
     * @return void
     */
    public function testCannotRegisterUserWithoutPassword(): void
    {
        $this->postJson('api/v1/users')
            ->assertStatus(422)
            ->assertJsonFragment(['password' => ['The password field is required.']]);
    }

    /**
     * @return void
     */
    public function testCannotRegisterUserWithoutPasswordConfirmation(): void
    {
        $this->postJson('api/v1/users', ['password' => str_random()])
            ->assertStatus(422)
            ->assertJsonFragment(['password' => ['The password confirmation does not match.']]);
    }

    /**
     * @return void
     */
    public function testCannotRegisterUserWithTooSmallPassword(): void
    {
        $password = '12345';

        $this->postJson('api/v1/users', ['password' => $password, 'password_confirmation' => $password])
            ->assertStatus(422)
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

        $this->postJson('api/v1/users', $visible + $hidden + ['password_confirmation' => $password])
            ->assertStatus(200)
            ->assertJsonFragment($visible)
            ->assertJsonMissing($hidden)
            ->assertJsonStructure(['id', 'name', 'email', 'created_at', 'updated_at']);

        $this->assertDatabaseHas('users', $visible);
        $this->assertDatabaseMissing('users', $hidden);
    }
}