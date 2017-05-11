<?php

namespace Social\Http\Actions\Users;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\JsonResponse;
use Social\Contracts\UserRepository;
use Social\Http\Requests\Users\RegisterUserRequest;

/**
 * Class RegisterUserAction
 * @package Social\Http\Actions\Users
 */
class RegisterUserAction
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var Hasher
     */
    private $hasher;

    /**
     * RegisterUserAction constructor.
     * @param UserRepository $userRepository
     * @param Hasher $hasher
     */
    public function __construct(UserRepository $userRepository, Hasher $hasher)
    {
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
    }

    /**
     * @param RegisterUserRequest $registerUserRequest
     * @return JsonResponse
     */
    public function __invoke(RegisterUserRequest $registerUserRequest): JsonResponse
    {
        [$name, $email, $password] = array_values($registerUserRequest->only(['name', 'email', 'password']));

        return new JsonResponse(
            $this->userRepository->register($name, $email, $this->hasher->make($password))->toArray()
        );
    }
}