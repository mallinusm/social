<?php

namespace Social\Http\Actions\Users;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\UserRepository;
use Social\Contracts\Transformers\UserTransformer;
use Social\Models\User;

/**
 * Class UpdateUserAction
 * @package Social\Http\Actions\Users
 */
final class UpdateUserAction
{
    use ValidatesRequests;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserTransformer
     */
    private $userTransformer;

    /**
     * UpdateUserSettingsAction constructor.
     * @param UserRepository $userRepository
     * @param UserTransformer $userTransformer
     */
    public function __construct(UserRepository $userRepository, UserTransformer $userTransformer)
    {
        $this->userRepository = $userRepository;
        $this->userTransformer = $userTransformer;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function __invoke(Request $request): array
    {
        /**
         * @var User $user
         */
        $user = $request->user();
        $userId = $user->getId();

        $this->validate($request, [
            'name' => 'required_without_all:email,username|string|max:255',
            'email' => 'required_without_all:name,username|string|email|max:255|unique:users,email,' . $userId,
            'username' => 'required_without_all:name,email|string|max:255|unique:users,username,' . $userId,
        ]);

        [$username, $name, $email] = array_values($request->only(['username', 'name', 'email']));

        $this->userRepository->update($userId, $username, $name, $email);

        $userEntity = $user->toUserEntity();

        if ($username !== null) {
            $userEntity->setUsername($username);
        }

        if ($name !== null) {
            $userEntity->setName($name);
        }

        if ($email !== null) {
            $userEntity->setEmail($email);
        }

        return $this->userTransformer->transformWithEmail($userEntity);
    }
}
