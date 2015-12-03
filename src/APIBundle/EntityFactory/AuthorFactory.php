<?php

namespace APIBundle\EntityFactory;

use APIBundle\EntityFactoryInterface;
use APIBundle\Graph\Node\Html;
use APIBundle\Graph\Node\Author;
use APIBundle\Graph\Relationship\PageAuthor;
use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use Innmind\Rest\Server\HttpResourceInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AuthorFactory implements EntityFactoryInterface
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
        return $resource->has('author');
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

        $author = new Author;
        $author->setName($resource->get('author'));
        $relationship = new PageAuthor;
        $relationship
            ->setAuthor($author)
            ->setPage($entity)
            ->setDate(new \DateTime);

        $this->dispatcher->dispatch(
            Events::RELATIONSHIP_BUILD,
            new RelationshipBuildEvent($relationship)
        );
    }
}
