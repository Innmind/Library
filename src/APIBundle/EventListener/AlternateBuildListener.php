<?php

namespace APIBundle\EventListener;

use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use APIBundle\Graph\Relationship\Alternate;
use Innmind\Neo4j\ONM\EntityManagerInterface;
use Innmind\Neo4j\ONM\Query;
use Pdp\Parser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AlternateBuildListener implements EventSubscriberInterface
{
    protected $em;
    protected $parser;

    public function __construct(EntityManagerInterface $em, Parser $parser)
    {
        $this->em = $em;
        $this->parser = $parser;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::RELATIONSHIP_BUILD => [['replaceAlternate', 50]],
        ];
    }

    /**
     * Use the alredy existing resource (if found) at the given url instead
     * of creating a new one
     *
     * @param RelationshipBuildEvent $event
     *
     * @return void
     */
    public function replaceAlternate(RelationshipBuildEvent $event)
    {
        $rel = $event->getRelationship();

        if (!$rel instanceof Alternate) {
            return;
        }

        $parsed = $this->parser->parseUrl($rel->getUrl());
        $query = new Query(<<<CYPHER
MATCH (r:Resource)--(h:Host)
WHERE
    r.path = {where}.path AND
    r.query = {where}.query AND
    h.host = {where}.host
RETURN r
CYPHER
        );
        $query
            ->addVariable('r', 'Resource')
            ->addVariable('h', 'Host')
            ->addParameters(
                'where',
                [
                    'path' => $parsed->path,
                    'query' => $parsed->query,
                    'host' => (string) $parsed->host,
                ],
                [
                    'path' => 'r.path',
                    'query' => 'r.query',
                    'host' => 'h.host',
                ]
            );
        $resources = $this
            ->em
            ->getUnitOfWork()
            ->execute($query);

        if ($resources->count() !== 1) {
            return;
        }

        $rel->setSource($resources->current());
    }
}
