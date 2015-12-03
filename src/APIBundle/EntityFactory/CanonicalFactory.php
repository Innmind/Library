<?php

namespace APIBundle\EntityFactory;

use APIBundle\EntityFactoryInterface;
use APIBundle\Graph\Node\HttpResource;
use APIBundle\Graph\Relationship\Canonical;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use Innmind\Rest\Server\HttpResourceInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CanonicalFactory implements EntityFactoryInterface
{
    protected $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(HttpResourceInterface $resource)
    {
        return $resource->has('canonical');
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

        $canonicResource = new HttpResource;
        $relationship = new Canonical;
        $relationship
            ->setSource($canonicResource)
            ->setDestination($entity)
            ->setUrl($resource->get('canonical'));

        $this->dispatcher->dispatch(
            Events::RELATIONSHIP_BUILD,
            new RelationshipBuildEvent($relationship)
        );
    }
}
