<?php

namespace APIBundle\EventListener;

use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use APIBundle\Graph\Relationship\PageAuthor;
use APIBundle\Graph\Node\Author;
use Innmind\Neo4j\ONM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AuthorBuildListener implements EventSubscriberInterface
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
            Events::RELATIONSHIP_BUILD => [['replaceAuthor', 50]],
        ];
    }

    /**
     * Use the alredy existing author (if found) instead of creating a new one
     *
     * @param RelationshipBuildEvent $event
     *
     * @return void
     */
    public function replaceAuthor(RelationshipBuildEvent $event)
    {
        $rel = $event->getRelationship();

        if (!$rel instanceof PageAuthor) {
            return;
        }

        $author = $this
            ->em
            ->getRepository(Author::class)
            ->findOneByName($rel->getAuthor()->getName());

        if ($author === null) {
            return;
        }

        $rel->setAuthor($author);
    }
}
