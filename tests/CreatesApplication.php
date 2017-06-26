<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Foundation\Application;

/**
 * Trait CreatesApplication
 * @package Tests
 */
trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication()
    {
        return tap(require __DIR__ . '/../bootstrap/app.php', function(Application $app): void {
            $app->make(Kernel::class)->bootstrap();
        });
    }
}
