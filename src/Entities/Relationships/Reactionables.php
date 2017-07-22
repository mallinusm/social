<?php

namespace Social\Entities\Relationships;

use Doctrine\ORM\PersistentCollection;
use Social\Entities\Reactionable;

/**
 * Trait Reactionables
 * @package Social\Entities\Relationships
 */
trait Reactionables
{
    /**
     * @var Reactionable[]
     */
    private $reactionables;

    /**
     * @param Reactionable[] $reactionables
     * @return $this
     */
    public function setReactionables(array $reactionables)
    {
        $this->reactionables = $reactionables;

        return $this;
    }

    /**
     * @return Reactionable[]
     */
    public function getReactionables(): array
    {
        if ($this->reactionables instanceof PersistentCollection) {
            return $this->reactionables->toArray();
        }

        return $this->reactionables;
    }

    /**
     * @return bool
     */
    public function hasReactionables(): bool
    {
        return $this->reactionables !== null;
    }
}
