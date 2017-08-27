<?php

namespace Social\Http\Actions\Users;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Social\Contracts\Repositories\UserRepository;

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
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UploadAvatarAction constructor.
     * @param ImageManager $imageManager
     * @param UserRepository $userRepository
     */
    public function __construct(ImageManager $imageManager, UserRepository $userRepository)
    {
        $this->imageManager = $imageManager;
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

        $this->userRepository->updateAvatar($request->user()->getAuthIdentifier(), $hashName);

        return ['avatar' => $hashName];
    }
}
