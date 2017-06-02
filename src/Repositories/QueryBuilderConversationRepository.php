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

    /**
     * @param int $userId
     * @return Paginator
     */
    public function paginate(int $userId): Paginator
    {
        return (new Conversation)->newQuery()->whereHas('users', function(Builder $query) use($userId): void {
            $query->where('user_id', $userId);
        })->with(['messages' => function(Builder $query): void {
            $query->latest()->take(1);
        }])->with('messages.user', 'users')->latest()->paginate();
    }
}