<?php

namespace Social\Helpers;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Generator;
use ReflectionClass;

/**
 * Class DoctrineClassMetadataBuilderInvoker
 * @package Social\Helpers
 */
final class DoctrineClassMetadataBuilderInvoker
{
    /**
     * @var ReflectionClass
     */
    private $reflectionClass;

    /**
     * DoctrineClassMetadataBuilderGenerator constructor.
     * @param ReflectionClass $reflectionClass
     */
    public function __construct(ReflectionClass $reflectionClass)
    {
        $this->reflectionClass = $reflectionClass;
    }

    /**
     * @return string
     */
    private function getTableName(): string
    {
        return strtolower(str_plural($this->reflectionClass->getShortName()));
    }

    /**
     * @param string $attributeName
     * @return string
     */
    private function getAttributeType(string $attributeName): string
    {
        $type = (string) $this->reflectionClass->getMethod(camel_case('get' . $attributeName))->getReturnType();

        /**
         * Doctrine does not support 'int'.
         */
        return $type === 'int' ? 'integer' : $type;
    }

    /**
     * @param string $attributeName
     * @return array
     */
    private function getMapping(string $attributeName): array
    {
        return [
            'columnName' => (new UnderscoreNamingStrategy)->propertyToColumnName($attributeName)
        ];
    }

    /**
     * @return Generator
     */
    private function getAttributeNames(): Generator
    {
        foreach ($this->reflectionClass->getProperties() as $property) {
            yield $property->getName();
        }
    }

    /**
     * @param ClassMetadataBuilder $builder
     */
    public function __invoke(ClassMetadataBuilder $builder): void
    {
        $builder->setTable($this->getTableName());

        foreach ($this->getAttributeNames() as $attributeName) {
            if ($attributeName === 'id') {
                $builder->createField($attributeName, 'integer')->makePrimaryKey()->generatedValue()->build();

                continue;
            }

            $builder->addField(
                $attributeName, $this->getAttributeType($attributeName), $this->getMapping($attributeName)
            );
        }
    }
}
