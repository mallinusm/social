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
     * @return Builder
     */
    protected function getBuilder(): Builder
    {
        return $this->builder;
    }

    /**
     * @return int
     */
    protected function freshTimestamp(): int
    {
        return Carbon::now()->getTimestamp();
    }

    /**
     * @param array $attributes
     * @return array
     */
    protected function insert(array $attributes): array
    {
        $id = $this->getBuilder()->insertGetId($attributes);

        return $attributes + compact('id');
    }
}