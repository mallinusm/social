<?php

namespace Social\Repositories;

use Carbon\Carbon;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Doctrine\DBAL\Query\QueryBuilder as SqlQueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder as DqlQueryBuilder;

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
     * @return DqlQueryBuilder
     */
    protected function getDqlQueryBuilder(): DqlQueryBuilder
    {
        return $this->entityManager->createQueryBuilder();
    }

    /**
     * @return SqlQueryBuilder
     */
    protected function getSqlQueryBuilder(): SqlQueryBuilder
    {
        return $this->entityManager->getConnection()->createQueryBuilder();
    }

    /**
     * @return int
     */
    protected function lastInsertedId(): int
    {
        return (int) $this->entityManager->getConnection()->lastInsertId();
    }

    /**
     * @return Expr
     */
    protected function getDqlExpression(): Expr
    {
        return $this->getDqlQueryBuilder()->expr();
    }

    /**
     * @return ExpressionBuilder
     */
    protected function getSqlExpression(): ExpressionBuilder
    {
        return $this->getSqlQueryBuilder()->expr();
    }
}
