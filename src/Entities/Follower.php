<?php

namespace Social\Entities;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Class Follower
 * @package Social\Entities
 */
class Follower
{
    use Attributes\Id,
        Attributes\AuthorId,
        Attributes\UserId,
        Attributes\CreatedAt,
        Attributes\UpdatedAt;

    use Relationships\Author;

    /**
     * @param ClassMetadata $metadata
     * @return void
     */
    public static function loadMetadata(ClassMetadata $metadata): void
    {
        (new ClassMetadataBuilder($metadata))->setTable('followers')
            ->createField('id', 'integer')
            ->makePrimaryKey()
            ->generatedValue()
            ->build()
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
            ])
            ->createOneToOne('author', User::class)
            ->build();
    }
}
