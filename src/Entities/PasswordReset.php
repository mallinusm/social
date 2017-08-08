<?php

namespace Social\Entities;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Class PasswordReset
 * @package Social\Entities
 */
class PasswordReset
{
    use Attributes\Id,
        Attributes\Token,
        Attributes\Email,
        Attributes\CreatedAt;

    /**
     * @param ClassMetadata $metadata
     * @return void
     */
    public static function loadMetadata(ClassMetadata $metadata): void
    {
        (new ClassMetadataBuilder($metadata))->setTable('password_resets')
            ->createField('id', 'integer')
            ->makePrimaryKey()
            ->generatedValue()
            ->build()
            ->addField('token', 'string')
            ->addField('email', 'string')
            ->addField('createdAt', 'integer', [
                'columnName' => 'created_at'
            ]);
    }
}
