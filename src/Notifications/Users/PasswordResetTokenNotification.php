<?php

namespace Social\Notifications\Users;

use Exception;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

/**
 * Class PasswordResetTokenNotification
 * @package Social\Notifications\Users
 */
final class PasswordResetTokenNotification extends Notification
{
    /**
     * @var string
     */
    private $token;

    /**
     * PasswordResetTokenNotification constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return array
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * @return string
     * @throws Exception
     */
    private function getPasswordResetLink(): string
    {
        $domain = (string) env('FRONTEND_DOMAIN');

        if (empty($domain)) {
            throw new Exception('Empty FRONTEND_DOMAIN environment value.');
        }

        if (! Str::endsWith('/', $domain)) {
            $domain .= '/';
        }

        return $domain . 'password-reset?token=' . $this->token;
    }

    /**
     * @return MailMessage
     */
    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject('Reset your password on Social!')
            ->greeting('Hello!')
            ->line('Click the button below to reset your password!')
            ->action('Reset Password', $this->getPasswordResetLink())
            ->line('Thank you for using our application!');
    }
}
