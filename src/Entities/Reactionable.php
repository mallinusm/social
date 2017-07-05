<?php

namespace Social\Entities;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Class Reactionable
 * @package Social\Entities
 */
final class Reactionable
{
    use Attributes\Id,
        Attributes\UserId,
        Attributes\ReactionId,
        Attributes\ReactionableId,
        Attributes\ReactionableType,
        Attributes\CreatedAt,
        Attributes\UpdatedAt;

    /**
     * @param ClassMetadata $metadata
     * @return void
     */
    public static function loadMetadata(ClassMetadata $metadata): void
    {
        (new ClassMetadataBuilder($metadata))->setTable('reactionables')
            ->createField('id', 'integer')->makePrimaryKey()->generatedValue()->build()
            ->addField('reactionableType', 'string', [
                'columnName' => 'reactionable_type'
            ])
            ->addField('reactionId', 'integer', [
                'columnName' => 'reaction_id'
            ])
            ->addField('userId', 'integer', [
                'columnName' => 'user_id'
            ])
            ->addField('reactionableId', 'integer', [
                'columnName' => 'reactionable_id'
            ])
            ->addField('createdAt', 'integer', [
                'columnName' => 'created_at'
            ])
            ->addField('updatedAt', 'integer', [
                'columnName' => 'updated_at'
            ]);
    }
}
