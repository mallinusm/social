<?php

namespace Social\Policies;

use Social\Models\{
    Conversation, User
};

/**
 * Class MessagePolicy
 * @package Social\Policies
 */
class MessagePolicy
{
    /**
     * @param User $user
     * @param Conversation $conversation
     * @return bool
     */
    private function isUserInConversation(User $user, Conversation $conversation): bool
    {
        return $conversation->users()->wherePivot('user_id', $user->getAuthIdentifier())->exists();
    }

    /**
     * @param User $user
     * @param Conversation $conversation
     * @return bool
     */
    public function create(User $user, Conversation $conversation): bool
    {
        return $this->isUserInConversation($user, $conversation);
    }

    /**
     * @param User $user
     * @param Conversation $conversation
     * @return bool
     */
    public function read(User $user, Conversation $conversation): bool
    {
        return $this->isUserInConversation($user, $conversation);
    }
}