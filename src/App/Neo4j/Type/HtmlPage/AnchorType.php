<?php
declare(strict_types = 1);

namespace App\Neo4j\Type\HtmlPage;

use Domain\Entity\HtmlPage\Anchor;
use Innmind\Neo4j\ONM\{
    Type,
    Types
};
use Innmind\Immutable\{
    MapInterface,
    SetInterface,
    Set
};

final class AnchorType implements Type
{
    private static $identifiers;

    /**
     * {@inheritdoc}
     */
    public static function fromConfig(MapInterface $config, Types $types): Type
    {
        return new self;
    }

    /**
     * {@inheritdoc}
     */
    public function forDatabase($value)
    {
        return $value->value();
    }

    /**
     * {@inheritdoc}
     */
    public function fromDatabase($value)
    {
        return new Anchor((string) $value);
    }

    /**
     * {@inheritdoc}
     */
    public function isNullable(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function identifiers(): SetInterface
    {
        if (self::$identifiers === null) {
            self::$identifiers = (new Set('string'))->add('html_page_anchor');
        }

        return self::$identifiers;
    }
}
