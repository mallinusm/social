<?php

namespace Social\Contracts\Transformers;

use Social\Entities\User;

/**
 * Interface UserTransformer
 * @package Social\Contracts\Transformers
 */
interface UserTransformer
{
    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user): array;

    /**
     * @param User $user
     * @return array
     */
    public function transformWithEmail(User $user): array;

    /**
     * @param User[] $users
     * @return array
     */
    public function transformMany(array $users): array;

    /**
     * @param User $user
     * @return array
     */
    public function transformWithFollowerState(User $user): array;

    /**
     * @param User[] $users
     * @return array
     */
    public function transformManyWithFollowerStates(array $users): array;
}
