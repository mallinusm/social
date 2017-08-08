<?php

namespace Social\Mailables;

use Exception;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Str;

/**
 * Class PasswordResetTokenMailable
 * @package Social\Mailables
 */
final class PasswordResetTokenMailable extends Mailable
{
    /**
     * @var string
     */
    private $token;

    /**
     * PasswordResetTokenMailable constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     * @throws Exception
     */
    private function getPasswordResetLink(): string
    {
        $domain = (string) env('FRONTEND_DOMAIN');

        if (is_null($domain)) {
            throw new Exception('Empty FRONTEND_DOMAIN environment value.');
        }

        if (! Str::endsWith('/', $domain)) {
            $domain .= '/';
        }

        return $domain . 'password-reset?token=' . $this->token;
    }

    /**
     * @return void
     */
    public function build(): void
    {
        $this->view('mails.users.password_reset')
            ->subject('Reset your password on Social')
            ->with([
                'link' => $this->getPasswordResetLink()
            ]);
    }
}
