<?php

namespace APIBundle\EventListener;

use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use APIBundle\Graph\Relationship\HostOfDomain;
use APIBundle\Graph\Node\Domain;
use Innmind\Neo4j\ONM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DomainBuildListener implements EventSubscriberInterface
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
            Events::RELATIONSHIP_BUILD => [['replaceDomain', 50]],
        ];
    }

    /**
     * Use the alredy existing domain (if found) instead of creating a new one
     *
     * @param RelationshipBuildEvent $event
     *
     * @return void
     */
    public function replaceDomain(RelationshipBuildEvent $event)
    {
        $rel = $event->getRelationship();

        if (!$rel instanceof HostOfDomain) {
            return;
        }

        $domain = $this
            ->em
            ->getRepository(Domain::class)
            ->findOneBy([
                'domain' => $rel->getDomain()->getDomain(),
                'tld' => $rel->getDomain()->getTld(),
            ]);

        if ($domain === null) {
            return;
        }

        $rel->setDomain($domain);
    }
}
