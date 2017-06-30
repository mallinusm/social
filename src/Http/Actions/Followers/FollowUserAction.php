<?php

namespace Social\Http\Actions\Followers;

use Illuminate\Http\Request;
use Social\Models\User;
use Social\Repositories\DoctrineFollowerRepository;

/**
 * Class FollowUserAction
 * @package Social\Http\Actions\Followers
 */
final class FollowUserAction
{
    /**
     * @var DoctrineFollowerRepository
     */
    private $followerRepository;

    /**
     * FollowUserAction constructor.
     * @param DoctrineFollowerRepository $followerRepository
     */
    public function __construct(DoctrineFollowerRepository $followerRepository)
    {
        $this->followerRepository = $followerRepository;
    }

    /**
     * @param User $user
     * @param Request $request
     * @return array
     */
    public function __invoke(User $user, Request $request): array
    {
        // TODO check if already following

        $this->followerRepository->follow($request->user()->getAuthIdentifier(), $user->getAuthIdentifier());

        return [
            'message' => 'You are now following the user.'
        ];
    }
}
