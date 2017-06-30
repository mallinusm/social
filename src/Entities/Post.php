<?php

namespace Social\Entities;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Class Post
 * @package Social\Entities
 */
final class Post
{
    use Attributes\Id,
        Attributes\AuthorId,
        Attributes\Content,
        Attributes\UserId,
        Attributes\CreatedAt,
        Attributes\UpdatedAt;

    use Relationships\Author,
        Relationships\User,
        Relationships\Comments;

    /**
     * @param ClassMetadata $metadata
     * @return void
     */
    public static function loadMetadata(ClassMetadata $metadata): void
    {
        (new ClassMetadataBuilder($metadata))->setTable('posts')
            ->createField('id', 'integer')->makePrimaryKey()->generatedValue()->build()
            ->addField('content', 'string')
            ->addField('authorId', 'integer', [
                'columnName' => 'author_id'
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
