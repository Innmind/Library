<?php

namespace APIBundle\EntityFactory;

use APIBundle\EntityFactoryInterface;
use APIBundle\Graph\Node\Host;
use APIBundle\Graph\Node\Domain;
use APIBundle\Graph\Relationship\HostOfDomain;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use Innmind\Rest\Server\HttpResourceInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DomainFactory implements EntityFactoryInterface
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
        return $resource->has('domain') && $resource->has('tld');
    }

    /**
     * {@inheritdoc}
     */
    public function build(HttpResourceInterface $resource, $entity)
    {
        if (!$entity instanceof Host) {
            throw new \InvalidArgumentException(sprintf(
                'Expecting an entity of type %s',
                Host::class
            ));
        }

        $domain = new Domain;
        $domain
            ->setDomain($resource->get('domain'))
            ->setTld($resource->get('tld'));
        $relationship = new HostOfDomain;
        $relationship
            ->setHost($entity)
            ->setDomain($domain);

        $this->dispatcher->dispatch(
            Events::RELATIONSHIP_BUILD,
            new RelationshipBuildEvent($relationship)
        );
    }
}
