<?php

namespace Social\Http\Actions\Users;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Social\Contracts\UserRepository;

/**
 * Class UploadAvatarAction
 * @package Social\Http\Actions\Users
 */
final class UploadAvatarAction
{
    use ValidatesRequests;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UploadAvatarAction constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function __invoke(Request $request): array
    {
        $this->validate($request, [
            'avatar' => 'required|image'
        ]);

        $path = $request->file('avatar')->store('public/avatars');

        $avatar = Str::replaceFirst('public/avatars/', '', $path);

        $this->userRepository->updateAvatar($request->user()->getAuthIdentifier(), $avatar);

        return compact('avatar');
    }
}
