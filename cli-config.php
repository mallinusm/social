<?php

/**
 * This configuration is used by the Doctrine binary located in vendor/bin/doctrine.
 */

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Illuminate\Contracts\Console\Kernel as KernelContract;
use Illuminate\Contracts\Foundation\Application;
use Social\Console\Kernel;

require_once __DIR__ . '/bootstrap/autoload.php';

/* @var Application $app */
$app = require_once __DIR__. '/bootstrap/app.php';

/* @var Kernel $kernel */
$kernel = $app->make(KernelContract::class);
$kernel->bootstrap();

/* @var EntityManagerInterface $entityManager */
$entityManager = $app->make(EntityManagerInterface::class);

return ConsoleRunner::createHelperSet($entityManager);
