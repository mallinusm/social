<?php

namespace Social\Helpers;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use ReflectionClass;

/**
 * Trait InteractsWithDoctrine
 * @package Social\Helpers
 */
trait InteractsWithDoctrine
{
    /**
     * @param ClassMetadata $metadata
     * @return void
     */
    public static function loadMetadata(ClassMetadata $metadata): void
    {
        $reflectionClass = new ReflectionClass(self::class);

        $builder = new ClassMetadataBuilder($metadata);

        $invoker = new DoctrineClassMetadataBuilderInvoker($reflectionClass);

        $invoker($builder);
    }
}
