<?php

namespace Social\Repositories;

use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class DoctrineRepository
 * @package Social\Repositories
 */
abstract class DoctrineRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * DoctrineUserRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return int
     */
    protected function freshTimestamp(): int
    {
        return Carbon::now()->getTimestamp();
    }

    /**
     * @param $object
     * @return mixed
     */
    protected function persist($object)
    {
        return tap($object, function($object) {
            $this->entityManager->persist($object);
            $this->entityManager->flush();
        });
    }
}
