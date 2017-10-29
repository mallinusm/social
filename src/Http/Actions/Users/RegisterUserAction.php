<?php

namespace Social\Http\Actions\Users;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\Repositories\UserRepository;
use Social\Contracts\Services\TransformerService;
use Social\Transformers\Users\UserTransformer;

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
     * @var Hasher
     */
    private $hasher;

    /**
     * @var TransformerService
     */
    private $transformerService;

    /**
     * RegisterUserAction constructor.
     * @param UserRepository $userRepository
     * @param Hasher $hasher
     * @param TransformerService $transformerService
     */
    public function __construct(UserRepository $userRepository,
                                Hasher $hasher,
                                TransformerService $transformerService)
    {
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
        $this->transformerService = $transformerService;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function __invoke(Request $request): string
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|max:255|confirmed'
        ]);

        $password = $this->hasher->make($request->input('password'));

        $email = $request->input('email');

        $name = $request->input('name');

        $username = $request->input('username');

        $user = $this->userRepository->register($email, $name, $password, $username);

        return $this->transformerService
            ->setData($user)
            ->setTransformer(UserTransformer::class)
            ->toJson();
    }
}
