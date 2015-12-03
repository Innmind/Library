<?php

namespace APIBundle\EventListener;

use APIBundle\EntityFactoryInterface;
use Innmind\Rest\Server\Event\EntityBuildEvent;
use Innmind\Rest\Server\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EntityBuilderListener implements EventSubscriberInterface
{
    protected $entityFactory;

    public function __construct(EntityFactoryInterface $entityFactory)
    {
        $this->entityFactory = $entityFactory;
    }

    /**
     * {@inehritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::ENTITY_BUILD => 'buildEntity',
        ];
    }

    /**
     * Use an entity factory to build the wished entity
     *
     * Happens when there's not a one to one mapping bewteen resource properties
     * and entities properties
     *
     * @param EntityBuildEvent $event
     *
     * @return void
     */
    public function buildEntity(EntityBuildEvent $event)
    {
        $resource = $event->getResource();

        if (!$this->entityFactory->supports($resource)) {
            return;
        }

        $this->entityFactory->build($resource, $event->getEntity());
        $event->stopPropagation();
    }
}
