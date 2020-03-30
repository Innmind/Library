<?php

use Innmind\Rest\Server\{
    Definition\Directory,
    Definition\HttpResource,
    Definition\Gateway,
    Definition\Identity,
    Definition\Property,
    Definition\Access,
    Definition\AllowedLink,
    Definition\Type\StringType,
    Definition\Type\SetType,
    Definition\Type\MapType,
    Definition\Type\IntType,
    Definition\Type\BoolType,
    Action,
};
use Innmind\Immutable\{
    Set,
    Map,
};

/**
 * @psalm-suppress InvalidArgument
 * @psalm-suppress InvalidScalarArgument
 */
return Directory::of(
    'web',
    Set::of(Directory::class),
    HttpResource::rangeable(
        'resource',
        new Gateway('http_resource'),
        new Identity('identity'),
        Set::of(
            Property::class,
            Property::required(
                'identity',
                new StringType,
                new Access(Access::READ)
            ),
            Property::required(
                'host',
                new StringType,
                new Access(Access::READ, Access::CREATE)
            ),
            Property::required(
                'path',
                new StringType,
                new Access(Access::READ, Access::CREATE)
            ),
            Property::required(
                'query',
                new StringType,
                new Access(Access::READ, Access::CREATE)
            ),
            Property::optional(
                'languages',
                new SetType('string', new StringType),
                new Access(Access::READ, Access::CREATE)
            ),
            Property::optional(
                'charset',
                new StringType,
                new Access(Access::READ, Access::CREATE)
            )
        ),
        Set::of(Action::class, Action::get(), Action::create(), Action::link()),
        Set::of(AllowedLink::class, new AllowedLink('referrer', 'web.resource')),
        Map::of('scalar', 'scalar|array')
            ('allowed_media_types', ['*/*; q=0.1'])
    ),
    HttpResource::rangeable(
        'image',
        new Gateway('image'),
        new Identity('identity'),
        Set::of(
            Property::class,
            Property::required(
                'identity',
                new StringType,
                new Access(Access::READ)
            ),
            Property::required(
                'host',
                new StringType,
                new Access(Access::READ, Access::CREATE)
            ),
            Property::required(
                'path',
                new StringType,
                new Access(Access::READ, Access::CREATE)
            ),
            Property::required(
                'query',
                new StringType,
                new Access(Access::READ, Access::CREATE)
            ),
            Property::optional(
                'descriptions',
                new SetType('string', new StringType),
                new Access(Access::READ, Access::CREATE)
            ),
            Property::optional(
                'dimension',
                new MapType(
                    'string',
                    'int',
                    new StringType,
                    new IntType
                ),
                new Access(Access::READ, Access::CREATE)
            ),
            Property::optional(
                'weight',
                new IntType,
                new Access(Access::READ, Access::CREATE)
            )
        ),
        Set::of(Action::class, Action::get(), Action::create()),
        null,
        Map::of('scalar', 'scalar|array')
            ('allowed_media_types', ['image/*'])
    ),
    HttpResource::rangeable(
        'html_page',
        new Gateway('html_page'),
        new Identity('identity'),
        Set::of(
            Property::class,
            Property::required(
                'identity',
                new StringType,
                new Access(Access::READ)
            ),
            Property::required(
                'host',
                new StringType,
                new Access(Access::READ, Access::CREATE)
            ),
            Property::optional(
                'author',
                new StringType,
                new Access(Access::READ, Access::CREATE)
            ),
            Property::optional(
                'citations',
                new SetType('string', new StringType),
                new Access(Access::READ, Access::CREATE)
            ),
            Property::required(
                'path',
                new StringType,
                new Access(Access::READ, Access::CREATE)
            ),
            Property::required(
                'query',
                new StringType,
                new Access(Access::READ, Access::CREATE)
            ),
            Property::optional(
                'languages',
                new SetType('string', new StringType),
                new Access(Access::READ, Access::CREATE)
            ),
            Property::optional(
                'charset',
                new StringType,
                new Access(Access::READ, Access::CREATE)
            ),
            Property::optional(
                'main_content',
                new StringType,
                new Access(Access::CREATE)
            ),
            Property::optional(
                'description',
                new StringType,
                new Access(Access::READ, Access::CREATE)
            ),
            Property::optional(
                'anchors',
                new SetType('string', new StringType),
                new Access(Access::READ, Access::CREATE)
            ),
            Property::optional(
                'theme_colour',
                new StringType,
                new Access(Access::READ, Access::CREATE)
            ),
            Property::optional(
                'title',
                new StringType,
                new Access(Access::READ, Access::CREATE)
            ),
            Property::optional(
                'android_app_link',
                new StringType,
                new Access(Access::READ, Access::CREATE)
            ),
            Property::optional(
                'ios_app_link',
                new StringType,
                new Access(Access::READ, Access::CREATE)
            ),
            Property::optional(
                'preview',
                new StringType,
                new Access(Access::READ, Access::CREATE)
            ),
            Property::optional(
                'is_journal',
                new BoolType,
                new Access(Access::CREATE)
            )
        ),
        Set::of(Action::class, Action::get(), Action::create(), Action::link()),
        Set::of(
            AllowedLink::class,
            new AllowedLink(
                'alternate',
                'web.html_page',
                new AllowedLink\Parameter('language')
            ),
            new AllowedLink('canonical', 'web.html_page')
        ),
        Map::of('scalar', 'scalar|array')
            ('allowed_media_types', [
                'text/html',
                'text/xml',
                'application/xml',
                'application/xhtml+xml',
            ])
    )
);
