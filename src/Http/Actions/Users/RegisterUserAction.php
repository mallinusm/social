<?php

namespace Social\Http\Actions\Users;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\UserRepository;
use Social\Contracts\Transformers\UserTransformer;

/**
 * Class RegisterUserAction
 * @package Social\Http\Actions\Users
 */
final class RegisterUserAction
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
     * @var Hasher
     */
    private $hasher;

    /**
     * RegisterUserAction constructor.
     * @param UserRepository $userRepository
     * @param UserTransformer $userTransformer
     * @param Hasher $hasher
     */
    public function __construct(UserRepository $userRepository, UserTransformer $userTransformer, Hasher $hasher)
    {
        $this->userRepository = $userRepository;
        $this->userTransformer = $userTransformer;
        $this->hasher = $hasher;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function __invoke(Request $request): array
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|max:255|confirmed'
        ]);

        $password = $this->hasher->make($request->input('password'));

        $user = $this->userRepository->register(
            $request->input('email'), $request->input('name'), $password, $request->input('username')
        );

        return $this->userTransformer->transform($user);
    }
}
