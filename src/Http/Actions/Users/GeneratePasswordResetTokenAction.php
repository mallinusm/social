<?php

namespace Social\Http\Actions\Users;

use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Notifications\AnonymousNotifiable;
use Social\Contracts\Repositories\UserRepository;
use Social\Notifications\Users\PasswordResetTokenNotification;

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
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * GeneratePasswordResetTokenAction constructor.
     * @param UserRepository $userRepository
     * @param Dispatcher $dispatcher
     */
    public function __construct(UserRepository $userRepository, Dispatcher $dispatcher)
    {
        $this->userRepository = $userRepository;
        $this->dispatcher = $dispatcher;
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

        $this->dispatcher->send(
            (new AnonymousNotifiable)->route('mail', $email),
            new PasswordResetTokenNotification($token)
        );

        return ['message' => 'Email sent.'];
    }
}
