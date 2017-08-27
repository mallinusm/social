<?php

namespace Social\Http\Actions\Users;

use Illuminate\Contracts\Filesystem\{
    FileNotFoundException,
    Filesystem
};
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class FetchAvatarAction
 * @package Social\Http\Actions\Users
 */
final class FetchAvatarAction
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * FetchAvatarAction constructor.
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $avatar
     * @return BinaryFileResponse
     * @throws FileNotFoundException
     */
    public function __invoke(string $avatar): BinaryFileResponse
    {
        if ($this->filesystem->exists('public/avatars/' . $avatar)) {
            return new BinaryFileResponse(storage_path('app/public/avatars/' . $avatar));
        }

        throw new FileNotFoundException('The avatar does not exist.');
    }
}
