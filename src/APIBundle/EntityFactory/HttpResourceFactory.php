<?php

namespace APIBundle\EntityFactory;

use APIBundle\EntityFactoryInterface;
use APIBundle\Graph\Node\HttpResource;
use Innmind\Rest\Server\HttpResourceInterface;

class HttpResourceFactory implements EntityFactoryInterface
{
    protected $hostFactory;
    protected $alternatesFactory;
    protected $canonicalFactory;

    public function __construct(
        HostFactory $hostFactory,
        AlternatesFactory $alternatesFactory,
        CanonicalFactory $canonicalFactory
    ) {
        $this->hostFactory = $hostFactory;
        $this->alternatesFactory = $alternatesFactory;
        $this->canonicalFactory = $canonicalFactory;
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

        return $definition->getOption('class') === HttpResource::class;
    }

    /**
     * {@inheritdoc}
     */
    public function build(HttpResourceInterface $resource, $entity)
    {
        if (!$entity instanceof HttpResource) {
            throw new \InvalidArgumentException(sprintf(
                'Expecting an entity of type %s',
                HttpResource::class
            ));
        }

        $entity
            ->setScheme($resource->get('scheme'))
            ->setPort($resource->get('port'))
            ->setPath($resource->get('path'))
            ->setQuery($resource->get('query'))
            ->setCharset($resource->get('charset'));

        if ($this->hostFactory->supports($resource)) {
            $this->hostFactory->build($resource, $entity);
        }

        if ($this->alternatesFactory->supports($resource)) {
            $this->alternatesFactory->build($resource, $entity);
        }

        if ($this->canonicalFactory->supports($resource)) {
            $this->canonicalFactory->build($resource, $entity);
        }
    }
}
