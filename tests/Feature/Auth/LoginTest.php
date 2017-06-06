<?php

namespace Tests\Feature\Auth;

use Laravel\Passport\ClientRepository;
use Tests\Feature\FeatureTestCase;

/**
 * Class LoginTest
 * @package Tests\Feature\Auth
 */
class LoginTest extends FeatureTestCase
{
    /**
     * @return void
     */
    public function testCannotLoginWithInvalidCredentials(): void
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/oauth/token')
            ->assertStatus(400);
    }

    /**
     * @return void
     */
    public function testCanLogin(): void
    {
        $client = (new ClientRepository)->createPasswordGrantClient(
            null, config('app.name'), config('app.url')
        );

        $this->assertDatabaseHas('oauth_clients', $client->toArray());

        $password = str_random();

        $user = $this->createUser(['password' => bcrypt($password)]);

        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/oauth/token', [
                'grant_type' => 'password',
                'client_id' => $client->getAttribute('id'),
                'client_secret' => $client->getAttribute('secret'),
                'username' => $user->getEmail(),
                'password' => $password
            ])
            ->assertStatus(200)
            ->assertJsonStructure(['token_type', 'expires_in', 'access_token', 'refresh_token']);
    }
}
