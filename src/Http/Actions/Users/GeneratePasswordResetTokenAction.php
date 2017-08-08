<?php

namespace Social\Http\Actions\Users;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\UserRepository;
use Social\Mailables\PasswordResetTokenMailable;

/**
 * Class GeneratePasswordResetTokenAction
 * @package Social\Http\Actions\Users
 */
final class GeneratePasswordResetTokenAction
{
    use ValidatesRequests;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * GeneratePasswordResetTokenAction constructor.
     * @param UserRepository $userRepository
     * @param Mailer $mailer
     */
    public function __construct(UserRepository $userRepository, Mailer $mailer)
    {
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function __invoke(Request $request): array
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:255|exists:users,email',
        ]);

        $email = $request->input('email');

        $token = $this->userRepository->generatePasswordResetToken($email);

        $mailable = new PasswordResetTokenMailable($token);

        $this->mailer->to($email)->send($mailable);

        return ['message' => 'Email sent.'];
    }
}
