<?php

namespace Social\Contracts;

use Social\Models\Conversation;

/**
 * Interface ConversationRepository
 * @package Social\Contracts
 */
interface ConversationRepository
{
    /**
     * @param array $userIds
     * @return Conversation
     */
    public function start(array $userIds): Conversation;
}