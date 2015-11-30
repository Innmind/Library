<?php

namespace APIBundle\EventListener;

use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use APIBundle\Graph\Relationship\CitedIn;
use APIBundle\Graph\Node\Citation;
use Innmind\Neo4j\ONM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CitationBuildListener implements EventSubscriberInterface
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
            Events::RELATIONSHIP_BUILD => [['replaceCitation', 50]],
        ];
    }

    /**
     * Use the alredy existing citation (if found) instead of creating a new one
     *
     * @param RelationshipBuildEvent $event
     *
     * @return void
     */
    public function replaceCitation(RelationshipBuildEvent $event)
    {
        $rel = $event->getRelationship();

        if (!$rel instanceof CitedIn) {
            return;
        }

        $citation = $this
            ->em
            ->getRepository(Citation::class)
            ->findOneByText($rel->getCitation()->getText());

        if ($citation === null) {
            return;
        }

        $rel->setCitation($citation);
    }
}
