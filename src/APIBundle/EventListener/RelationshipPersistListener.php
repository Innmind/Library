<?php

namespace APIBundle\EventListener;

use APIBundle\Events as ApiEvents;
use APIBundle\Event\RelationshipBuildEvent;
use Innmind\Neo4j\ONM\EntityManagerInterface;
use Innmind\Rest\Server\Events;
use Innmind\Rest\Server\Event\Storage\PreCreateEvent;
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
            Events::STORAGE_PRE_CREATE => 'enable',
            Events::STORAGE_POST_CREATE => 'disable',
            ApiEvents::RELATIONSHIP_BUILD => 'persist',
        ];
    }

    /**
     * Enable the persistance of relationships
     *
     * @param PreCreateEvent $event
     *
     * @return void
     */
    public function enable(PreCreateEvent $event)
    {
        if ($event->getResource()->getDefinition()->getStorage() === 'neo4j') {
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
