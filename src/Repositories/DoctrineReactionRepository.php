<?php

namespace Social\Repositories;

use Doctrine\ORM\EntityNotFoundException;
use Social\Contracts\ReactionRepository;
use Social\Entities\Reaction;

/**
 * Class DoctrineReactionRepository
 * @package Social\Repositories
 */
final class DoctrineReactionRepository extends DoctrineRepository implements ReactionRepository
{
    /**
     * @param string $name
     * @return int
     * @throws EntityNotFoundException
     */
    private function getReactionId(string $name): int
    {
        $repository = $this->getEntityManager()->getRepository(Reaction::class);

        $reaction = $repository->findOneBy(compact('name'));

        if ($reaction === null) {
            throw EntityNotFoundException::fromClassNameAndIdentifier($repository->getClassName(), []);
        }

        /**
         * @var Reaction $reaction
         */
        return $reaction->getId();
    }

    /**
     * @return int
     */
    public function getUpvoteId(): int
    {
        return $this->getReactionId('upvote');
    }

    /**
     * @return int
     */
    public function getDownvoteId(): int
    {
        return $this->getReactionId('downvote');
    }
}
