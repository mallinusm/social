<?php

namespace Social\Http\Actions\Users;

use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\UserRepository;
use Social\Entities\User;
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

        $notification = new PasswordResetTokenNotification($token);

        $notifiable = (new User)->setEmail($email);

        $this->dispatcher->send($notifiable, $notification);

        return ['message' => 'Email sent.'];
    }
}
