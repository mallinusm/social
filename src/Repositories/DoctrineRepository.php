<?php

namespace Social\Repositories;

use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Class DoctrineRepository
 * @package Social\Repositories
 */
abstract class DoctrineRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * DoctrineUserRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return EntityManagerInterface
     */
    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
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

    /**
     * @param $object
     * @return void
     */
    protected function remove($object): void
    {
        $this->entityManager->remove($object);
        $this->entityManager->flush();
    }

    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder();
    }
}
