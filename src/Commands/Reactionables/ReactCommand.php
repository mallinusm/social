<?php

namespace Social\Commands\Reactionables;

/**
 * Class ReactCommand
 * @package Social\Commands\Reactionables
 */
final class ReactCommand
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @var int
     */
    private $reactionId;

    /**
     * @var int
     */
    private $reactionableId;

    /**
     * @var string
     */
    private $reactionableType;

    /**
     * ReactionCommand constructor.
     * @param int $userId
     * @param int $reactionId
     * @param int $reactionableId
     * @param string $reactionableType
     */
    public function __construct(int $userId, int $reactionId, int $reactionableId, string $reactionableType)
    {
        $this->userId = $userId;
        $this->reactionId = $reactionId;
        $this->reactionableId = $reactionableId;
        $this->reactionableType = $reactionableType;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getReactionId(): int
    {
        return $this->reactionId;
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
}
