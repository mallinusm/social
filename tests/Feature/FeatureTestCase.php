<?php

namespace Tests\Feature;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * Class FeatureTestCase
 * @package Tests\Feature
 */
abstract class FeatureTestCase extends TestCase
{
    use CreatesModels, DatabaseMigrations, LaravelConcerns;

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
            /**
             * Before running the feature tests we make sure there's a folder for saving the avatars.
             */
            $this->getFilesystem()->makeDirectory(self::AVATARS_DIR);
        });

        parent::setUp();
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        $this->beforeApplicationDestroyed(function(): void {
            /**
             * After running the feature tests we make sure to clean up the avatars folder.
             */
            $this->getFilesystem()->deleteDirectory(self::AVATARS_DIR);
        });

        parent::tearDown();
    }
}
