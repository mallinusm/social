<?php

namespace Tests\Feature;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Pagination\Paginator;
use Tests\TestCase;

/**
 * Class FeatureTestCase
 * @package Tests\Feature
 */
abstract class FeatureTestCase extends TestCase
{
    use DatabaseMigrations, CreatesModels;

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
}