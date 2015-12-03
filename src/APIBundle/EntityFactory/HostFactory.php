<?php

namespace APIBundle\EntityFactory;

use APIBundle\EntityFactoryInterface;
use APIBundle\Graph\Node\HttpResource;
use APIBundle\Graph\Node\Host;
use APIBundle\Graph\Relationship\ResourceOfHost;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use Innmind\Rest\Server\HttpResourceInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class HostFactory implements EntityFactoryInterface
{
    protected $dispatcher;
    protected $domainFactory;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        DomainFactory $domainFactory
    ) {
        $this->dispatcher = $dispatcher;
        $this->domainFactory = $domainFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(HttpResourceInterface $resource)
    {
        return $resource->has('host');
    }

    /**
     * {@inheritdoc}
     */
    public function build(HttpResourceInterface $resource, $entity)
    {
        if (!$entity instanceof HttpResource) {
            throw new \InvalidArgumentException(sprintf(
                'Expecting an entity of type %s',
                HttpResource::class
            ));
        }

        $host = new Host;
        $host->setHost($resource->get('host'));
        $relationship = new ResourceOfHost;
        $relationship
            ->setResource($entity)
            ->setHost($host)
            ->setDate(new \DateTime);

        $this->dispatcher->dispatch(
            Events::RELATIONSHIP_BUILD,
            new RelationshipBuildEvent($relationship)
        );

        if ($this->domainFactory->supports($resource)) {
            $this->domainFactory->build($resource, $host);
        }
    }
}
