<?php

namespace Social\Contracts;

/**
 * Interface ReactionRepository
 * @package Social\Contracts
 */
interface ReactionRepository
{
    /**
     * @return int
     */
    function getUpvoteId(): int;

    /**
     * @return int
     */
    function getDownvoteId(): int;
}
