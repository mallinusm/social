<?php

namespace Social\Repositories;

use Illuminate\Contracts\Pagination\Paginator;
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
            'user_id' => $userId
        ]));
    }

    /**
     * @param int $conversationId
     * @return Paginator
     */
    public function paginate(int $conversationId): Paginator
    {
        return (new Message)->newQuery()
            ->where('conversation_id', $conversationId)
            ->with('user')
            ->latest()
            ->simplePaginate();
    }
}
