<?php

namespace Tests\Feature\Auth;

use Laravel\Passport\{
    Client, ClientRepository
};
use Tests\Feature\FeatureTestCase;

/**
 * Class LoginTest
 * @package Tests\Feature\Auth
 */
class LoginTest extends FeatureTestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Create a password grant client.
     */
    function setUp()
    {
        parent::setUp();

        $this->client = (new ClientRepository)->createPasswordGrantClient(
            null, config('app.name'), config('app.url')
        );
    }

    /** @test */
    function login_without_grant_type()
    {
        $this->assertGuest('api')
            ->postJson('api/v1/oauth/token')
            ->assertStatus(400)
            ->assertJsonFragment([
                'message' => 'The authorization grant type is not supported by the authorization server.'
            ]);
    }

    /** @test */
    function login_with_unknown_grant_type()
    {
        $this->assertGuest('api')
            ->postJson('api/v1/oauth/token', ['grant_type' => str_random()])
            ->assertStatus(400)
            ->assertJsonFragment([
                'message' => 'The authorization grant type is not supported by the authorization server.'
            ]);
    }

    /** @test */
    function login_without_client_id()
    {
        $this->assertGuest('api')
            ->postJson('api/v1/oauth/token', ['grant_type' => 'password'])
            ->assertStatus(400);
    }

    /** @test */
    function login_with_unknown_client_id()
    {
        $this->assertGuest('api')
            ->postJson('api/v1/oauth/token', ['grant_type' => 'password', 'client_id' => str_random()])
            ->assertStatus(401)
            ->assertJsonFragment(['message' => 'Client authentication failed']);
    }

    /** @test */
    function login_without_client_secret()
    {
        $this->assertGuest('api')
            ->postJson('api/v1/oauth/token', [
                'grant_type' => 'password',
                'client_id' => $this->client->getAttribute('id')
            ])
            ->assertStatus(401)
            ->assertJsonFragment(['message' => 'Client authentication failed']);
    }

    /** @test */
    function login_with_unknown_client_secret()
    {
        $this->assertGuest('api')
            ->postJson('api/v1/oauth/token', [
                'grant_type' => 'password',
                'client_id' => $this->client->getAttribute('id'),
                'client_secret' => str_random()
            ])
            ->assertStatus(401)
            ->assertJsonFragment(['message' => 'Client authentication failed']);
    }

    /** @test */
    function login_without_credentials()
    {
        $this->assertGuest('api')
            ->postJson('api/v1/oauth/token', [
                'grant_type' => 'password',
                'client_id' => $this->client->getAttribute('id'),
                'client_secret' => $this->client->getAttribute('secret')
            ])
            ->assertStatus(400);
    }

    /** @test */
    function login_with_invalid_credentials()
    {
        $this->assertGuest('api')
            ->postJson('api/v1/oauth/token', [
                'grant_type' => 'password',
                'client_id' => $this->client->getAttribute('id'),
                'client_secret' => $this->client->getAttribute('secret'),
                'username' => str_random(),
                'password' => str_random()
            ])
            ->assertStatus(401)
            ->assertJsonFragment(['message' => 'The user credentials were incorrect.']);
    }

    /** @test */
    function login_with_email()
    {
        $password = str_random();

        $user = $this->createUser(['password' => bcrypt($password)]);

        $this->assertGuest('api')
            ->postJson('api/v1/oauth/token', [
                'grant_type' => 'password',
                'client_id' => $this->client->getAttribute('id'),
                'client_secret' => $this->client->getAttribute('secret'),
                'username' => $user->getEmail(),
                'password' => $password
            ])
            ->assertStatus(200)
            ->assertJsonStructure($this->oauthJsonStructure());
    }

    /** @test */
    function login_with_username()
    {
        $password = str_random();

        $user = $this->createUser(['password' => bcrypt($password)]);

        $this->assertGuest('api')
            ->postJson('api/v1/oauth/token', [
                'grant_type' => 'password',
                'client_id' => $this->client->getAttribute('id'),
                'client_secret' => $this->client->getAttribute('secret'),
                'username' => $user->getUsername(),
                'password' => $password
            ])
            ->assertStatus(200)
            ->assertJsonStructure($this->oauthJsonStructure());
    }
}
