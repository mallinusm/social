<?php

namespace Social\Contracts;

use Illuminate\Contracts\Pagination\Paginator;
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
    function start(array $userIds): Conversation;

    /**
     * @param int $userId
     * @return Paginator
     */
    function paginate(int $userId): Paginator;
}
