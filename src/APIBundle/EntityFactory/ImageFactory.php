<?php

namespace APIBundle\EntityFactory;

use APIBundle\EntityFactoryInterface;
use APIBundle\Graph\Node\Image;
use Innmind\Rest\Server\HttpResourceInterface;

class ImageFactory implements EntityFactoryInterface
{
    protected $resourceFactory;

    public function __construct(HttpResourceFactory $resourceFactory)
    {
        $this->resourceFactory = $resourceFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(HttpResourceInterface $resource)
    {
        $definition = $resource->getDefinition();

        if (!$definition->hasOption('class')) {
            return false;
        }

        return $definition->getOption('class') === Image::class;
    }

    /**
     * {@inheritdoc}
     */
    public function build(HttpResourceInterface $resource, $entity)
    {
        if (!$entity instanceof Image) {
            throw new \InvalidArgumentException(sprintf(
                'Expecting an entity of type %s',
                Image::class
            ));
        }

        $this->resourceFactory->build($resource, $entity);

        $entity
            ->setWidth($resource->get('width'))
            ->setHeight($resource->get('height'))
            ->setMime($resource->get('mime'))
            ->setExtension($resource->get('extension'))
            ->setWeight($resource->get('weight'))
            ->setExif(json_decode($resource->get('exif'), true));
    }
}
