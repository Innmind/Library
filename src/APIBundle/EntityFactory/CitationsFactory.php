<?php

namespace APIBundle\EntityFactory;

use APIBundle\EntityFactoryInterface;
use APIBundle\Graph\Node\Html;
use APIBundle\Graph\Node\Citation;
use APIBundle\Graph\Relationship\CitedIn;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use Innmind\Rest\Server\HttpResourceInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CitationsFactory implements EntityFactoryInterface
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
        if (!$resource->has('citations')) {
            return false;
        }

        return count($resource->get('citations')) > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function build(HttpResourceInterface $resource, $entity)
    {
        if (!$entity instanceof Html) {
            throw new \InvalidArgumentException(sprintf(
                'Expecting an entity of type %s',
                Html::class
            ));
        }

        foreach ($resource->get('citations') as $cite) {
            $citation = new Citation;
            $citation->setText($cite);
            $relationship = new CitedIn;
            $relationship
                ->setCitation($citation)
                ->setResource($entity)
                ->setDate(new \DateTime);

            $this->dispatcher->dispatch(
                Events::RELATIONSHIP_BUILD,
                new RelationshipBuildEvent($relationship)
            );
        }
    }
}
