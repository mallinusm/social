<?php

namespace Social\Entities;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Class User
 * @package Social\Entities
 */
class User
{
    use Attributes\Id,
        Attributes\Email,
        Attributes\Name,
        Attributes\Password,
        Attributes\Avatar,
        Attributes\Username,
        Attributes\CreatedAt,
        Attributes\UpdatedAt;

    use Relationships\Posts;

    /**
     * @param ClassMetadata $metadata
     * @return void
     */
    public static function loadMetadata(ClassMetadata $metadata): void
    {
        (new ClassMetadataBuilder($metadata))->setTable('users')
            ->createField('id', 'integer')->makePrimaryKey()->generatedValue()->build()
            ->addField('email', 'string')
            ->addField('name', 'string')
            ->addField('password', 'string')
            ->addField('avatar', 'string')
            ->addField('username', 'string')
            ->addField('createdAt', 'integer', [
                'columnName' => 'created_at'
            ])
            ->addField('updatedAt', 'integer', [
                'columnName' => 'updated_at'
            ])
            ->createOneToMany('posts', Post::class)
            ->mappedBy('user')
            ->build();
    }
}
