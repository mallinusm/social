<?php

namespace Social\Entities;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Exception;

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

    use Relationships\Posts,
        Relationships\Followers,
        Relationships\Followings;

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
            ->build()
            ->createOneToMany('followers', Follower::class)
            ->mappedBy('user')
            ->build()
            ->createOneToMany('followings', Follower::class)
            ->mappedBy('author')
            ->build();
    }

    /**
     * @param string $driver
     * @return string
     * @throws Exception
     */
    public function routeNotificationFor(string $driver): string
    {
        if ($driver === 'mail') {
            return $this->getEmail();
        }

        throw new Exception('Invalid driver [' . $driver . '].');
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->getEmail();
    }
}
