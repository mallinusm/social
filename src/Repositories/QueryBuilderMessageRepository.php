<?php

namespace Social\Repositories;

use Social\Contracts\MessageRepository;
use Social\Models\Message;

/**
 * Class QueryBuilderMessageRepository
 * @package Social\Repositories
 */
class QueryBuilderMessageRepository extends QueryBuilderRepository implements MessageRepository
{
    /**
     * @return string
     */
    protected function getTable(): string
    {
        return 'messages';
    }

    /**
     * @param string $content
     * @param int $conversationId
     * @param int $userId
     * @return Message
     */
    public function send(string $content, int $conversationId, int $userId): Message
    {
        return (new Message)->fill($this->insert([
            'conversation_id' => $conversationId,
            'content' => $content,
            'created_at' => $now = $this->freshTimestamp(),
            'updated_at' => $now,
            'user_id' => $userId
        ]));
    }
}