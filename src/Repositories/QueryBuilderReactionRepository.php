<?php

namespace Social\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Social\Contracts\ReactionRepository;
use Social\Models\{
    Reaction, ReactionType
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
        return 'reactions';
    }

    /**
     * @param string $name
     * @return int
     */
    public function getReactionTypeId(string $name): int
    {
        $id = (clone $this->getBuilder())
            ->from('reaction_types')
            ->where('name', $name)
            ->value('id');

        if ($id === null) {
            throw (new ModelNotFoundException)->setModel(ReactionType::class);
        }

        return (int) $id;
    }

    /**
     * @param int $reactionableId
     * @param string $reactionableType
     * @param int $reactionTypeId
     * @param int $userId
     * @return bool
     */
    public function hasReacted(int $reactionableId, string $reactionableType, int $reactionTypeId, int $userId): bool
    {
        return $this->getBuilder()
            ->where('reactionable_id', $reactionableId)
            ->where('reactionable_type', $reactionableType)
            ->where('reaction_type_id', $reactionTypeId)
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * @param int $reactionableId
     * @param string $reactionableType
     * @param int $reactionTypeId
     * @param int $userId
     * @return Reaction
     */
    public function react(int $reactionableId, string $reactionableType, int $reactionTypeId, int $userId): Reaction
    {
        return (new Reaction)->fill($this->insert([
            'reactionable_id' => $reactionableId,
            'reactionable_type' => $reactionableType,
            'reaction_type_id' => $reactionTypeId,
            'user_id' => $userId
        ]));
    }
}
