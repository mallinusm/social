<?php

namespace Social\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Social\Contracts\ReactionRepository;
use Social\Models\{
    Reactionable, Reaction
};

/**
 * Class QueryBuilderReactionRepository
 * @package Social\Repositories
 */
class QueryBuilderReactionRepository extends QueryBuilderRepository implements ReactionRepository
{
    /**
     * @return string
     */
    protected function getTable(): string
    {
        return 'reactionables';
    }

    /**
     * @param string $name
     * @return int
     * @throws ModelNotFoundException
     */
    public function getReactionId(string $name): int
    {
        $id = $this->getBuilder('reactions')->where('name', $name)->value('id');

        if ($id === null) {
            throw (new ModelNotFoundException)->setModel(Reaction::class);
        }

        return (int) $id;
    }

    /**
     * @param int $reactionableId
     * @param string $reactionableType
     * @param int $reactionId
     * @param int $userId
     * @return bool
     */
    public function hasReacted(int $reactionableId, string $reactionableType, int $reactionId, int $userId): bool
    {
        return $this->getBuilder()
            ->where('reactionable_id', $reactionableId)
            ->where('reactionable_type', $reactionableType)
            ->where('reaction_id', $reactionId)
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * @param int $reactionableId
     * @param string $reactionableType
     * @param int $reactionId
     * @param int $userId
     * @return Reactionable
     */
    public function react(int $reactionableId, string $reactionableType, int $reactionId, int $userId): Reactionable
    {
        return (new Reactionable)->fill($this->insert([
            'reactionable_id' => $reactionableId,
            'reactionable_type' => $reactionableType,
            'reaction_id' => $reactionId,
            'user_id' => $userId
        ]));
    }

    /**
     * @param int $reactionableId
     * @param string $reactionableType
     * @param int $reactionId
     * @param int $userId
     * @return bool
     */
    public function undoReaction(int $reactionableId, string $reactionableType, int $reactionId, int $userId): bool
    {
        return (bool) $this->getBuilder()
            ->where('reactionable_id', $reactionableId)
            ->where('reactionable_type', $reactionableType)
            ->where('reaction_id', $reactionId)
            ->where('user_id', $userId)
            ->delete();
    }
}
