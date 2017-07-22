<?php

namespace Social\Http\Actions\Users;

use Illuminate\Http\Request;
use Social\Models\User;
use Social\Transformers\UserTransformer;

/**
 * Class FetchCurrentUserAction
 * @package Social\Http\Actions\Users
 */
final class FetchCurrentUserAction
{
    /**
     * @var UserTransformer
     */
    private $userTransformer;

    /**
     * FetchCurrentUserAction constructor.
     * @param UserTransformer $userTransformer
     */
    public function __construct(UserTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function __invoke(Request $request): array
    {
        /**
         * @var User $user
         */
        $user = $request->user();

        return $this->userTransformer->transform($user->toUserEntity());
    }
}
