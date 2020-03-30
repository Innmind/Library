<?php
declare(strict_types = 1);

use Domain\Entity;
use Innmind\Neo4j\ONM\{
    Metadata\Aggregate,
    Metadata\Aggregate\Child,
    Metadata\Relationship,
    Metadata\ClassName,
    Metadata\Identity,
    Metadata\RelationshipType,
    Metadata\RelationshipEdge,
    Type,
    Type\StringType,
    Type\PointInTimeType,
    Type\BooleanType,
    Type\SetType,
};
use Innmind\Immutable\{
    Map,
    Set,
};

/** @psalm-suppress InvalidArgument */
return [
    Aggregate::of(
        new ClassName(Entity\Author::class),
        new Identity('identity', App\Entity\Author\Identity::class),
        Set::of('string', 'Person', 'Author'),
        Map::of('string', Type::class)
            ('name', new App\Neo4j\Type\Author\NameType)
    ),
    Aggregate::of(
        new ClassName(Entity\Citation::class),
        new Identity('identity', App\Entity\Citation\Identity::class),
        Set::of('string', 'Citation'),
        Map::of('string', Type::class)
            ('text', new App\Neo4j\Type\Citation\TextType)
    ),
    Relationship::of(
        new ClassName(Entity\CitationAppearance::class),
        new Identity('identity', App\Entity\CitationAppearance\Identity::class),
        new RelationshipType('CITED_IN_RESOURCE'),
        new RelationshipEdge('citation', App\Entity\Citation\Identity::class, 'identity'),
        new RelationshipEdge('resource', App\Entity\HttpResource\Identity::class, 'identity'),
        Map::of('string', Type::class)
            ('foundAt', new PointInTimeType)
    ),
    Aggregate::of(
        new ClassName(Entity\Domain::class),
        new Identity('identity', App\Entity\Domain\Identity::class),
        Set::of('string', 'Web', 'Domain'),
        Map::of('string', Type::class)
            ('name', new App\Neo4j\Type\Domain\NameType)
            ('tld', new App\Neo4j\Type\Domain\TopLevelDomainType)
    ),
    Aggregate::of(
        new ClassName(Entity\Host::class),
        new Identity('identity', App\Entity\Host\Identity::class),
        Set::of('string', 'Web', 'Host'),
        Map::of('string', Type::class)
            ('name', new App\Neo4j\Type\Host\NameType)
    ),
    Relationship::of(
        new ClassName(Entity\DomainHost::class),
        new Identity('identity', App\Entity\DomainHost\Identity::class),
        new RelationshipType('HOST_OF_DOMAIN'),
        new RelationshipEdge('host', App\Entity\Host\Identity::class, 'identity'),
        new RelationshipEdge('domain', App\Entity\Domain\Identity::class, 'identity'),
        Map::of('string', Type::class)
            ('foundAt', new PointInTimeType)
    ),
    Aggregate::of(
        new ClassName(Entity\HtmlPage::class),
        new Identity('identity', App\Entity\HtmlPage\Identity::class),
        Set::of('string', 'Web', 'Resource', 'Html'),
        Map::of('string', Type::class)
            ('path', new App\Neo4j\Type\HttpResource\PathType)
            ('query', new App\Neo4j\Type\HttpResource\QueryType)
            ('languages', new SetType(
                new App\Neo4j\Type\LanguageType,
                \Domain\Model\Language::class
            ))
            ('charset', new App\Neo4j\Type\HttpResource\CharsetType)
            ('mainContent', new StringType)
            ('description', new StringType)
            ('anchors', new SetType(
                new App\Neo4j\Type\HtmlPage\AnchorType,
                \Domain\Entity\HtmlPage\Anchor::class
            ))
            ('themeColour', App\Neo4j\Type\ColourType::nullable())
            ('title', new StringType)
            ('android', App\Neo4j\Type\UrlType::nullable())
            ('ios', App\Neo4j\Type\UrlType::nullable())
            ('preview', App\Neo4j\Type\UrlType::nullable())
            ('isJournal', new BooleanType)
    ),
    Aggregate::of(
        new ClassName(Entity\HttpResource::class),
        new Identity('identity', App\Entity\HttpResource\Identity::class),
        Set::of('string', 'Web', 'Resource'),
        Map::of('string', Type::class)
            ('path', new App\Neo4j\Type\HttpResource\PathType)
            ('query', new App\Neo4j\Type\HttpResource\QueryType)
            ('languages', new SetType(
                new App\Neo4j\Type\LanguageType,
                \Domain\Model\Language::class
            ))
            ('charset', new App\Neo4j\Type\HttpResource\CharsetType)
    ),
    Relationship::of(
        new ClassName(Entity\HostResource::class),
        new Identity('identity', App\Entity\HostResource\Identity::class),
        new RelationshipType('RESOURCE_OF_HOST'),
        new RelationshipEdge('resource', App\Entity\HttpResource\Identity::class, 'identity'),
        new RelationshipEdge('host', App\Entity\Host\Identity::class, 'identity'),
        Map::of('string', Type::class)
            ('foundAt', new PointInTimeType)
    ),
    Relationship::of(
        new ClassName(Entity\ResourceAuthor::class),
        new Identity('identity', App\Entity\ResourceAuthor\Identity::class),
        new RelationshipType('AUTHOR_OF_RESOURCE'),
        new RelationshipEdge('author', App\Entity\Author\Identity::class, 'identity'),
        new RelationshipEdge('resource', App\Entity\HttpResource\Identity::class, 'identity'),
        Map::of('string', Type::class)
            ('asOf', new PointInTimeType)
    ),
    Relationship::of(
        new ClassName(Entity\Alternate::class),
        new Identity('identity', App\Entity\Alternate\Identity::class),
        new RelationshipType('ALTERNATE_OF_RESOURCE'),
        new RelationshipEdge('alternate', App\Entity\HttpResource\Identity::class, 'identity'),
        new RelationshipEdge('resource', App\Entity\HttpResource\Identity::class, 'identity'),
        Map::of('string', Type::class)
            ('language', new App\Neo4j\Type\LanguageType)
    ),
    Relationship::of(
        new ClassName(Entity\Canonical::class),
        new Identity('identity', App\Entity\Canonical\Identity::class),
        new RelationshipType('CANONICAL_OF_RESOURCE'),
        new RelationshipEdge('canonical', App\Entity\HttpResource\Identity::class, 'identity'),
        new RelationshipEdge('resource', App\Entity\HttpResource\Identity::class, 'identity'),
        Map::of('string', Type::class)
            ('foundAt', new PointInTimeType)
    ),
    Relationship::of(
        new ClassName(Entity\Reference::class),
        new Identity('identity', App\Entity\Reference\Identity::class),
        new RelationshipType('RESOURCE_REFERENCED_IN_RESOURCE'),
        new RelationshipEdge('source', App\Entity\HttpResource\Identity::class, 'identity'),
        new RelationshipEdge('target', App\Entity\HttpResource\Identity::class, 'identity')
    ),
    Aggregate::of(
        new ClassName(Entity\Image::class),
        new Identity('identity', App\Entity\Image\Identity::class),
        Set::of('string', 'Web', 'Resource', 'Image'),
        Map::of('string', Type::class)
            ('path', new App\Neo4j\Type\HttpResource\PathType)
            ('query', new App\Neo4j\Type\HttpResource\QueryType)
            ('languages', new SetType(
                new App\Neo4j\Type\LanguageType,
                \Domain\Model\Language::class
            ))
            ('charset', new App\Neo4j\Type\HttpResource\CharsetType)
            ('dimension', new App\Neo4j\Type\Image\DimensionType)
            ('weight', new App\Neo4j\Type\Image\WeightType)
            ('descriptions', new SetType(
                new App\Neo4j\Type\Image\DescriptionType,
                \Domain\Entity\Image\Description::class
            ))
    ),
];
