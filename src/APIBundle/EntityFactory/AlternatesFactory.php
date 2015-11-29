<?php

namespace APIBundle\EntityFactory;

use APIBundle\EntityFactoryInterface;
use APIBundle\Graph\Node\HttpResource;
use APIBundle\Graph\Relationship\Alternate;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use Innmind\Rest\Server\HttpResourceInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AlternatesFactory implements EntityFactoryInterface
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
        if (!$resource->has('alternates')) {
            return false;
        }

        return count($resource->get('alternates')) > 0;
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

        $alternates = $resource->get('alternates');

        foreach ($alternates as $alternate) {
            $alternateResource = new HttpResource;
            $relationship = new Alternate;
            $relationship
                ->setSource($alternateResource)
                ->setDestination($entity)
                ->setLanguage($alternate->get('language'))
                ->setUrl($alternate->get('url'))
                ->setDate(new \DateTime);

            $this->dispatcher->dispatch(
                Events::RELATIONSHIP_BUILD,
                new RelationshipBuildEvent($relationship)
            );
        }
    }
}
