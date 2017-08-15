<?php

namespace Social\Entities;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Class Reactionable
 * @package Social\Entities
 */
class Reactionable
{
    use Attributes\Id,
        Attributes\UserId,
        Attributes\ReactionId,
        Attributes\ReactionableId,
        Attributes\ReactionableType,
        Attributes\CreatedAt,
        Attributes\UpdatedAt;

    use Relationships\Post,
        Relationships\Comment,
        Relationships\User;

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
            ])
            ->createOneToOne('user', User::class)
            ->addJoinColumn('user_id', 'id')
            ->build()
            ->createManyToOne('post', Post::class)
            ->inversedBy('reactionables')
            ->addJoinColumn('reactionable_id', 'id')
            ->build()
            ->createManyToOne('comment', Comment::class)
            ->inversedBy('reactionables')
            ->addJoinColumn('reactionable_id', 'id')
            ->build();
    }
}
