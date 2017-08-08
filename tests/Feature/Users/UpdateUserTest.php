<?php


namespace Tests\Feature\Users;

use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class UpdateUserTest
 * @package Tests\Feature\Users
 */
class UpdateUserTest extends FeatureTestCase
{
    /** @test */
    function update_user_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->patch('api/v1/user')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function update_user_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->patchJson('api/v1/user')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function update_user_without_attributes()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->patchJson('api/v1/user')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'email' => [
                    'The email field is required when none of name / username are present.'
                ],
                'name' => [
                    'The name field is required when none of email / username are present.'
                ],
                'username' => [
                    'The username field is required when none of name / email are present.'
                ]
            ]);
    }

    /** @test */
    function update_user_with_too_long_name()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->patchJson('api/v1/user', ['name' => str_random(256)])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'name' => [
                    'The name may not be greater than 255 characters.'
                ]
            ]);
    }

    /** @test */
    function update_user_with_invalid_email()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->patchJson('api/v1/user', ['email' => str_random()])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'email' => [
                    'The email must be a valid email address.'
                ]
            ]);
    }

    /** @test */
    function update_user_with_taken_email()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->patchJson('api/v1/user', ['email' => $this->createUser()->getEmail()])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'email' => [
                    'The email has already been taken.'
                ]
            ]);
    }

    /** @test */
    function update_user_with_too_long_username()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->patchJson('api/v1/user', ['username' => str_random(256)])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'username' => [
                    'The username may not be greater than 255 characters.'
                ]
            ]);
    }

    /** @test */
    function update_user_with_taken_username()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->patchJson('api/v1/user', ['username' => $this->createUser()->getUsername()])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'username' => [
                    'The username has already been taken.'
                ]
            ]);
    }

    /** @test */
    function update_user()
    {
        $user = $this->createUser(['updated_at' => 1]);

        [$username, $email, $name] = [str_random(), str_random() . '@mail.com', str_random()];

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->patchJson('api/v1/user', [
                'username' => $username,
                'email' => $email,
                'name' => $name
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'username' => $username,
                'email' => $email,
                'name' => $name,
                'avatar' => $user->getAvatar()
            ]);

        $updatedAt = $user->fresh()->getUpdatedAt();
        $this->assertGreaterThan($user->getUpdatedAt(), $updatedAt);

        $this->assertDatabaseHas('users', [
            'id' => $user->getId(),
            'username' => $username,
            'name' => $name,
            'email' => $email
        ]);
    }

    /** @test */
    function update_user_name()
    {
        $user = $this->createUser(['updated_at' => 1]);

        $name = str_random();

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->patchJson('api/v1/user', ['name' => $name])
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'name' => $name,
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'avatar' => $user->getAvatar()
            ]);

        $updatedAt = $user->fresh()->getUpdatedAt();
        $this->assertGreaterThan($user->getUpdatedAt(), $updatedAt);
        $this->assertEquals(time(), $updatedAt);

        $this->assertDatabaseHas('users', [
            'id' => $user->getId(),
            'name' => $name
        ]);
    }

    /** @test */
    function update_user_username()
    {
        $user = $this->createUser(['updated_at' => 1]);

        $username = str_random();

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->patchJson('api/v1/user', ['username' => $username])
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'name' => $user->getName(),
                'username' => $username,
                'email' => $user->getEmail(),
                'avatar' => $user->getAvatar()
            ]);

        $updatedAt = $user->fresh()->getUpdatedAt();
        $this->assertGreaterThan($user->getUpdatedAt(), $updatedAt);
        $this->assertEquals(time(), $updatedAt);

        $this->assertDatabaseHas('users', [
            'id' => $user->getId(),
            'username' => $username
        ]);
    }

    /** @test */
    function update_user_email()
    {
        $user = $this->createUser(['updated_at' => 1]);

        $email = str_random() . '@mail.com';

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->patchJson('api/v1/user', ['email' => $email])
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'name' => $user->getName(),
                'username' => $user->getUsername(),
                'email' => $email,
                'avatar' => $user->getAvatar()
            ]);

        $updatedAt = $user->fresh()->getUpdatedAt();
        $this->assertGreaterThan($user->getUpdatedAt(), $updatedAt);
        $this->assertEquals(time(), $updatedAt);

        $this->assertDatabaseHas('users', [
            'id' => $user->getId(),
            'email' => $email
        ]);
    }
}
