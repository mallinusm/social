<?php

namespace Tests\Feature;

use Doctrine\ORM\EntityNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\Paginator;

/**
 * Trait LaravelConcerns
 * @package Tests\Feature
 */
trait LaravelConcerns
{
    /**
     * @return array
     */
    public function simplePaginationStructure(): array
    {
        return array_keys((new Paginator([], 15))->toArray());
    }

    /**
     * @param string $model
     * @return array
     */
    public function modelNotFoundMessage(string $model): array
    {
        return [
            'error' => (new ModelNotFoundException)->setModel($model)->getMessage()
        ];
    }

    /**
     * @param string $className
     * @return array
     */
    public function entityNotFound(string $className): array
    {
        return [
            'error' => EntityNotFoundException::fromClassNameAndIdentifier($className, [])->getMessage()
        ];
    }
}
