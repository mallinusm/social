<?php

namespace Social\Repositories;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
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
        $attributes = $this->insert();

        $conversationId = $attributes['id'];

        $now = $this->freshTimestamp();

        $this->getBuilder()->from('conversation_user')->insert(
            (new Collection($userIds))->transform(function($userId) use($conversationId, $now): array {
                return [
                    'conversation_id' => $conversationId,
                    'user_id' => $userId,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            })->all()
        );

        return (new Conversation)->fill($attributes);
    }

    /**
     * @param int $userId
     * @return Paginator
     */
    public function paginate(int $userId): Paginator
    {
        return (new Conversation)->newQuery()->whereHas('users', function(Builder $query) use($userId): void {
            $query->where('user_id', $userId);
        })->has('messages')->with(['messages' => function(Builder $query): void {
            $query->latest()->take(1);
        }])->with('messages.user', 'users')->latest()->paginate();
    }
}