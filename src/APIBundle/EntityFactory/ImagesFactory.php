<?php

namespace APIBundle\EntityFactory;

use APIBundle\EntityFactoryInterface;
use APIBundle\Graph\Node\Html;
use APIBundle\Graph\Node\Image;
use APIBundle\Graph\Relationship\PageImage;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use Innmind\Rest\Server\HttpResourceInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ImagesFactory implements EntityFactoryInterface
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
        if (!$resource->has('images')) {
            return false;
        }

        return count($resource->get('images')) > 0;
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

        foreach ($resource->get('images') as $image) {
            $imageResource = new Image;
            $relationship = new PageImage;
            $relationship
                ->setImage($imageResource)
                ->setPage($entity)
                ->setDescription($image->get('description'))
                ->setDate(new \DateTime)
                ->setUrl($image->get('url'));

            $this->dispatcher->dispatch(
                Events::RELATIONSHIP_BUILD,
                new RelationshipBuildEvent($relationship)
            );
        }
    }
}
