<?php

namespace Social\Http\Actions\Users;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\Repositories\UserRepository;
use Social\Contracts\Services\AuthenticationService;
use Social\Contracts\Services\TransformerService;
use Social\Transformers\Users\UserTransformer;

/**
 * Class UpdateUserAction
 * @package Social\Http\Actions\Users
 */
final class UpdateUserAction
{
    use ValidatesRequests;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var TransformerService
     */
    private $transformerService;

    /**
     * UpdateUserSettingsAction constructor.
     * @param AuthenticationService $authenticationService
     * @param UserRepository $userRepository
     * @param TransformerService $transformerService
     */
    public function __construct(AuthenticationService $authenticationService,
                                UserRepository $userRepository,
                                TransformerService $transformerService)
    {
        $this->authenticationService = $authenticationService;
        $this->userRepository = $userRepository;
        $this->transformerService = $transformerService;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function __invoke(Request $request): string
    {
        $user = $this->authenticationService->getAuthenticatedUser();

        $userId = $user->getId();

        $this->validate($request, [
            'name' => 'required_without_all:email,username|string|max:255',
            'email' => 'required_without_all:name,username|string|email|max:255|unique:users,email,' . $userId,
            'username' => 'required_without_all:name,email|string|max:255|unique:users,username,' . $userId,
        ]);

        $username = $request->input('username');

        $name = $request->input('name');

        $email = $request->input('email');

        $this->userRepository->update($userId, $username, $name, $email);

        if ($username !== null) {
            $user->setUsername($username);
        }

        if ($name !== null) {
            $user->setName($name);
        }

        if ($email !== null) {
            $user->setEmail($email);
        }

        return $this->transformerService
            ->setData($user)
            ->setTransformer((new UserTransformer)->withEmail())
            ->toJson();
    }
}
