<?php

namespace Social\Entities;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Class Comment
 * @package Social\Entities
 */
final class Comment
{
    use Attributes\Id,
        Attributes\Content,
        Attributes\PostId,
        Attributes\UserId,
        Attributes\CreatedAt,
        Attributes\UpdatedAt;

    use Relationships\User;

    /**
     * @param ClassMetadata $metadata
     * @return void
     */
    public static function loadMetadata(ClassMetadata $metadata): void
    {
        (new ClassMetadataBuilder($metadata))->setTable('comments')
            ->createField('id', 'integer')->makePrimaryKey()->generatedValue()->build()
            ->addField('content', 'string')
            ->addField('postId', 'integer', [
                'columnName' => 'post_id'
            ])
            ->addField('userId', 'integer', [
                'columnName' => 'user_id'
            ])
            ->addField('createdAt', 'integer', [
                'columnName' => 'created_at'
            ])
            ->addField('updatedAt', 'integer', [
                'columnName' => 'updated_at'
            ]);
    }
}
