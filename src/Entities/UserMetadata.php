<?php

namespace Social\Entities;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Trait UserMetadata
 * @package Social\Entities
 */
trait UserMetadata
{
    /**
     * @param ClassMetadata $metadata
     * @return void
     */
    public static function loadMetadata(ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable('users');
        $builder->createField('id', 'integer')->makePrimaryKey()->generatedValue()->build();
        $builder->addField('name', 'string');
        $builder->addField('email', 'string');
        $builder->addField('password', 'string');
        $builder->addField('avatar', 'string');
        $builder->addField('createdAt', 'integer', [
            'columnName' => 'created_at'
        ]);
        $builder->addField('updatedAt', 'integer', [
            'columnName' => 'updated_at'
        ]);
    }
}
