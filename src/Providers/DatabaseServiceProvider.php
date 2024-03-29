<?php

namespace Social\Providers;

use Doctrine\Common\Persistence\Mapping\Driver\StaticPHPDriver;
use Doctrine\DBAL\Driver\Connection as ConnectionContract;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\{
    Configuration,
    EntityManager,
    EntityManagerInterface
};
use Doctrine\ORM\Tools\Setup;
use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;

/**
 * Class DatabaseServiceProvider
 * @package Social\Providers
 */
final class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(EntityManagerInterface::class, function(): EntityManagerInterface {
            return EntityManager::create($this->getConnection(), $this->getConfiguration());
        });
    }

    /**
     * @return Configuration
     */
    private function getConfiguration(): Configuration
    {
        $configuration = Setup::createConfiguration(false, storage_path('doctrine'));

        $configuration->setMetadataDriverImpl(new StaticPHPDriver(base_path('src/Entities')));

        return $configuration;
    }

    /**
     * @return ConnectionContract
     */
    private function getConnection(): ConnectionContract
    {
        /* @var Connection $connection */
        $connection = $this->app->make(Connection::class);

        /**
         * Make sure the same database is used when using Eloquent and Doctrine ORM.
         */
        return DriverManager::getConnection([
            'pdo' => $connection->getPdo()
        ]);
    }
}
