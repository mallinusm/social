<?php

namespace Social\Entities;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Class Reaction
 * @package Social\Entities
 */
class Reaction
{
    use Attributes\Id,
        Attributes\Name,
        Attributes\CreatedAt,
        Attributes\UpdatedAt;

    /**
     * @param ClassMetadata $metadata
     * @return void
     */
    public static function loadMetadata(ClassMetadata $metadata): void
    {
        (new ClassMetadataBuilder($metadata))->setTable('reactions')
            ->createField('id', 'integer')
            ->makePrimaryKey()
            ->generatedValue()
            ->build()
            ->addField('name', 'string')
            ->addField('createdAt', 'integer', [
                'columnName' => 'created_at'
            ])
            ->addField('updatedAt', 'integer', [
                'columnName' => 'updated_at'
            ]);
    }
}
