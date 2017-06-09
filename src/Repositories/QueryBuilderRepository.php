<?php

namespace Social\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;

/**
 * Class QueryBuilderRepository
 * @package Social\Repositories
 */
abstract class QueryBuilderRepository
{
    /**
     * @var Builder
     */
    private $builder;

    /**
     * @return string
     */
    abstract protected function getTable(): string;

    /**
     * QueryBuilderRepository constructor.
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder->from($this->getTable());
    }

    /**
     * @param string|null $tableName
     * @return Builder
     */
    protected function getBuilder(string $tableName = null): Builder
    {
        if ($tableName === null) {
            return $this->builder;
        }

        /**
         * We need to clone the query builder because we are changing its table name property. If we change the table
         * name of the original query builder we will encounter unwanted behaviour, for instance the wrong table getting
         * queried in other methods, especially if this class is used as a singleton within the application.
         */
        return (clone $this->getBuilder())->from($tableName);
    }

    /**
     * @return int
     */
    protected function freshTimestamp(): int
    {
        return Carbon::now()->getTimestamp();
    }

    /**
     * @return array
     */
    private function getTimestamps(): array
    {
        return [
            'created_at' => $now = $this->freshTimestamp(),
            'updated_at' => $now
        ];
    }

    /**
     * @param array $attributes
     * @return array
     */
    protected function insert(array $attributes = []): array
    {
        $id = $this->getBuilder()->insertGetId($attributes += $this->getTimestamps());

        return $attributes + compact('id');
    }
}