<?php

namespace APIBundle\EntityFactory;

use APIBundle\EntityFactoryInterface;
use APIBundle\Graph\Node\Html;
use APIBundle\Graph\Node\HttpResource;
use APIBundle\Graph\Relationship\Referrer;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use Innmind\Rest\Server\HttpResourceInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class LinksFactory implements EntityFactoryInterface
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
        if (!$resource->has('links')) {
            return false;
        }

        return count($resource->get('links')) > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function build(HttpResourceInterface $resource, $entity)
    {
        if (!$entity instanceof Html) {
            throw new \InvalidArgumentException(sprintf(
                'Expecting an entity of type %s',
                Html::class
            ));
        }

        foreach ($resource->get('links') as $link) {
            $referred = new HttpResource;
            $relationship = new Referrer;
            $relationship
                ->setSource($entity)
                ->setDestination($referred)
                ->setUrl($link);

            $this->dispatcher->dispatch(
                Events::RELATIONSHIP_BUILD,
                new RelationshipBuildEvent($relationship)
            );
        }
    }
}
