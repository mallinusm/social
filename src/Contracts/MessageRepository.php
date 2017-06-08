<?php

namespace Social\Contracts;

use Illuminate\Contracts\Pagination\Paginator;
use Social\Models\Message;

/**
 * Interface MessageRepository
 * @package Social\Contracts
 */
interface MessageRepository
{
    /**
     * @param string $content
     * @param int $conversationId
     * @param int $userId
     * @return Message
     */
    function send(string $content, int $conversationId, int $userId): Message;

    /**
     * @param int $conversationId
     * @return Paginator
     */
    function paginate(int $conversationId): Paginator;
}
