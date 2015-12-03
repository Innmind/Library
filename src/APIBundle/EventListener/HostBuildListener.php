<?php

namespace APIBundle\EventListener;

use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use APIBundle\Graph\Relationship\ResourceOfHost;
use APIBundle\Graph\Node\Host;
use Innmind\Neo4j\ONM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class HostBuildListener implements EventSubscriberInterface
{
    protected $em;

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
            Events::RELATIONSHIP_BUILD => [['replaceHost', 50]],
        ];
    }

    /**
     * Use the alredy existing host (if found) instead of creating a new one
     *
     * @param RelationshipBuildEvent $event
     *
     * @return void
     */
    public function replaceHost(RelationshipBuildEvent $event)
    {
        $rel = $event->getRelationship();

        if (!$rel instanceof ResourceOfHost) {
            return;
        }

        $host = $this
            ->em
            ->getRepository(Host::class)
            ->findOneByHost($rel->getHost()->getHost());

        if ($host === null) {
            return;
        }

        $rel->setHost($host);
    }
}
