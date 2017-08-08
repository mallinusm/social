<?php

namespace Tests\Feature\Users;

use Illuminate\Support\Facades\Mail;
use Social\Mailables\PasswordResetTokenMailable;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class GeneratePasswordResetTokenTest
 * @package Tests\Feature\Users
 */
class GeneratePasswordResetTokenTest extends FeatureTestCase
{
    /** @test */
    function password_reset_token_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->post('api/v1/password-reset-token')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function password_reset_token_without_email()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/password-reset-token')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'email' => ['The email field is required.']
            ]);
    }

    /** @test */
    function password_reset_token_with_invalid_email()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/password-reset-token', ['email' => str_random()])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'email' => ['The email must be a valid email address.']
            ]);
    }

    /** @test */
    function password_reset_token_with_non_existing_email()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/password-reset-token', ['email' => str_random() . '@mail.com'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'email' => ['The selected email is invalid.']
            ]);
    }

    /** @test */
    function password_reset_token()
    {
        Mail::fake();

        $user = $this->createUser();
        $email = $user->getEmail();

        $data = compact('email');

        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/password-reset-token', $data)
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'message' => 'Email sent.'
            ]);

        $this->assertDatabaseHas('password_resets', $data);

        Mail::assertSent(PasswordResetTokenMailable::class, function(PasswordResetTokenMailable $mailable) use($email): bool {
            return $mailable->hasTo($email);
        });
    }
}
