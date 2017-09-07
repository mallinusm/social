<?php

namespace Tests\Features;

use Doctrine\ORM\EntityNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use ReactionsTableSeeder;
use Tests\TestCase;

/**
 * Class FeatureTestCase
 * @package Tests\Features
 */
abstract class FeatureTestCase extends TestCase
{
    use CreatesModels,
        DatabaseMigrations,
        AssertsNotifications,
        AssertsEvents,
        JsonStructures;

    /**
     * The storage path for saving avatars.
     */
    private const AVATARS_DIR = 'public/avatars';

    /**
     * @return Filesystem
     */
    protected function getFilesystem(): Filesystem
    {
        return $this->app->make(Filesystem::class);
    }

    /**
     * @return void
     */
    public function setUp()
    {
        $this->afterApplicationCreated(function(): void {
            $this->getFilesystem()->makeDirectory(self::AVATARS_DIR);

            $this->artisan('db:seed', [
                '--class' => ReactionsTableSeeder::class
            ]);
        });

        parent::setUp();
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        $this->beforeApplicationDestroyed(function(): void {
            $this->getFilesystem()->deleteDirectory(self::AVATARS_DIR);
        });

        parent::tearDown();
    }

    /**
     * @param string $avatar
     * @return string
     */
    protected function avatarUrl(string $avatar): string
    {
        /** @var UrlGenerator $urlGenerator */
        $urlGenerator = $this->app->make(UrlGenerator::class);

        return $urlGenerator->route('avatars.show', compact('avatar'));
    }

    /**
     * @param string $model
     * @return array
     */
    protected function modelNotFoundMessage(string $model): array
    {
        return [
            'error' => (new ModelNotFoundException)->setModel($model)->getMessage()
        ];
    }

    /**
     * @param string $className
     * @param array $ids
     * @return array
     */
    protected function entityNotFound(string $className, array $ids = []): array
    {
        return [
            'error' => EntityNotFoundException::fromClassNameAndIdentifier($className, $ids)->getMessage()
        ];
    }

    /**
     * @return array
     */
    protected function onlyJsonSupported(): array
    {
        return [
            'error' => 'Only json format is supported.'
        ];
    }

    /**
     * @return int
     */
    protected function getUpvoteId(): int
    {
        return 1;
    }

    /**
     * @return int
     */
    protected function getDownvoteId(): int
    {
        return 2;
    }
}
