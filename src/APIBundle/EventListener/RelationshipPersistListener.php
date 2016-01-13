<?php

namespace APIBundle\EventListener;

use APIBundle\Events as ApiEvents;
use APIBundle\Event\RelationshipBuildEvent;
use Innmind\Neo4j\ONM\EntityManagerInterface;
use Innmind\Rest\Server\Events;
use Innmind\Rest\Server\Event\Storage\PreCreateEvent;
use Innmind\Rest\Server\Event\Storage\PreUpdateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RelationshipPersistListener implements EventSubscriberInterface
{
    protected $em;
    protected $enabled = false;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::STORAGE_PRE_CREATE => 'onPreCreate',
            Events::STORAGE_POST_CREATE => 'disable',
            Events::STORAGE_PRE_UPDATE => 'onPreUpdate',
            Events::STORAGE_POST_UPDATE => 'disable',
            ApiEvents::RELATIONSHIP_BUILD => 'persist',
        ];
    }

    /**
     * Enable the persistance of relationships
     *
     * @param PreUpdateEvent $event
     *
     * @return void
     */
    public function onPreCreate(PreCreateEvent $event)
    {
        $this->enable($event->getResource()->getDefinition()->getStorage());
    }

    /**
     * Enable the persistance of relationships
     *
     * @param PreUpdateEvent $event
     *
     * @return void
     */
    public function onPreUpdate(PreUpdateEvent $event)
    {
        $this->enable($event->getResource()->getDefinition()->getStorage());
    }

    /**
     * Enable the persistence
     *
     * @param string $storage
     *
     * @return void
     */
    public function enable($storage)
    {
        if ((string) $storage === 'neo4j') {
            $this->enabled = true;
        }
    }

    /**
     * Disable the persistance
     *
     * @return void
     */
    public function disable()
    {
        $this->enabled = false;
    }

    /**
     * Persist a relationship to the graph database
     *
     * @param RelationshipBuildEvent $event
     *
     * @return void
     */
    public function persist(RelationshipBuildEvent $event)
    {
        if ($this->enabled === false) {
            return;
        }

        $this->em->persist($event->getRelationship());
    }
}
