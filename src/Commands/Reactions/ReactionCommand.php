<?php

namespace Social\Commands\Reactions;

/**
 * Class ReactionCommand
 * @package Social\Commands\Reactions
 */
class ReactionCommand
{
    /**
     * @var int
     */
    private $reactionableId;

    /**
     * @var string
     */
    private $reactionableType;

    /**
     * @var string
     */
    private $reactionName;

    /**
     * @var int
     */
    private $userId;

    /**
     * ReactionCommand constructor.
     * @param int $reactionableId
     * @param string $reactionableType
     * @param string $reactionName
     * @param int $userId
     */
    public function __construct(int $reactionableId, string $reactionableType, string $reactionName, int $userId)
    {
        $this->reactionableId = $reactionableId;
        $this->reactionableType = $reactionableType;
        $this->reactionName = $reactionName;
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getReactionableId(): int
    {
        return $this->reactionableId;
    }

    /**
     * @return string
     */
    public function getReactionableType(): string
    {
        return $this->reactionableType;
    }

    /**
     * @return string
     */
    public function getReactionName(): string
    {
        return $this->reactionName;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}
