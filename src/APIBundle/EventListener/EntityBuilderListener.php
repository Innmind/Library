<?php

namespace APIBundle\EventListener;

use APIBundle\EntityFactoryInterface;
use APIBundle\ResourceFactoryInterface;
use Innmind\Rest\Server\Event\EntityBuildEvent;
use Innmind\Rest\Server\Event\ResourceBuildEvent;
use Innmind\Rest\Server\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EntityBuilderListener implements EventSubscriberInterface
{
    protected $entityFactory;
    protected $resourceFactory;

    public function __construct(
        EntityFactoryInterface $entityFactory,
        ResourceFactoryInterface $resourceFactory
    ) {
        $this->entityFactory = $entityFactory;
        $this->resourceFactory = $resourceFactory;
    }

    /**
     * {@inehritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::ENTITY_BUILD => 'buildEntity',
            Events::RESOURCE_BUILD => 'buildResource',
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

    /**
     * Use a resource factory to build the wished resource
     *
     * @param ResourceBuildEvent $event
     *
     * @return void
     */
    public function buildResource(ResourceBuildEvent $event)
    {
        $data = $event->getData();
        $definition = $event->getDefinition();

        if (!$this->resourceFactory->supports($data, $definition)) {
            return;
        }

        $data = $this->resourceFactory->build($data, $definition);
        $event
            ->replaceData($data)
            ->stopPropagation();
    }
}
