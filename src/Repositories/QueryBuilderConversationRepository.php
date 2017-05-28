<?php

namespace Social\Repositories;

use Illuminate\Support\Collection;
use Social\Contracts\ConversationRepository;
use Social\Models\Conversation;

/**
 * Class QueryBuilderConversationRepository
 * @package Social\Repositories
 */
class QueryBuilderConversationRepository extends QueryBuilderRepository implements ConversationRepository
{
    /**
     * @return string
     */
    protected function getTable(): string
    {
        return 'conversations';
    }

    /**
     * @param array $userIds
     * @return Conversation
     */
    public function start(array $userIds): Conversation
    {
        $id = $this->getBuilder()->insertGetId($attributes = [
            'created_at' => $now = $this->freshTimestamp(),
            'updated_at' => $now
        ]);

        $now = $this->freshTimestamp();

        $this->getBuilder()->from('conversation_user')->insert(
            (new Collection($userIds))->transform(function($userId) use($id, $now): array {
                return [
                    'conversation_id' => $id,
                    'user_id' => $userId,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            })->all()
        );

        return (new Conversation)->fill($attributes + compact('id'));
    }
}