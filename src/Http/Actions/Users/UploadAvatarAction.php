<?php

namespace Social\Http\Actions\Users;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Social\Contracts\Repositories\UserRepository;
use Social\Contracts\Services\AuthenticationService;

/**
 * Class UploadAvatarAction
 * @package Social\Http\Actions\Users
 */
final class UploadAvatarAction
{
    use ValidatesRequests;

    /**
     * @var ImageManager
     */
    private $imageManager;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UploadAvatarAction constructor.
     * @param ImageManager $imageManager
     * @param AuthenticationService $authenticationService
     * @param UserRepository $userRepository
     */
    public function __construct(ImageManager $imageManager,
                                AuthenticationService $authenticationService,
                                UserRepository $userRepository)
    {
        $this->imageManager = $imageManager;
        $this->authenticationService = $authenticationService;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function __invoke(Request $request): array
    {
        $this->validate($request, [
            'avatar' => [
                'required',
                'image',
                'dimensions:ratio=1/1,min_width=128,max_width=1024,min_height=128,max_height=1024'
            ]
        ]);

        $avatar = $request->file('avatar');

        $hashName = $avatar->hashName();

        $this->imageManager
            ->make($avatar->path())
            ->resize(128, 128)
            ->save(storage_path('app/public/avatars/' . $hashName));

        $userId = $this->authenticationService->getAuthenticatedUser()->getId();

        $this->userRepository->updateAvatar($userId, $hashName);

        return ['avatar' => $hashName];
    }
}
