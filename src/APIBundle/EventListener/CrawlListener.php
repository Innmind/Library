<?php

namespace APIBundle\EventListener;

use APIBundle\Events;
use APIBundle\Event\RelationshipBuildEvent;
use APIBundle\Graph\Relationship\TargetableInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class CrawlListener implements EventSubscriberInterface
{
    protected $producer;
    protected $targets;

    public function __construct(ProducerInterface $producer)
    {
        $this->producer = $producer;
        $this->targets = new \SplObjectStorage;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::RELATIONSHIP_BUILD => ['register', -20],
            KernelEvents::TERMINATE => 'publish',
        ];
    }

    /**
     * Register all relationships implementing TargetableInterface to be crawled
     * once the current resource is being persisted
     *
     * @param RelationshipBuildEvent $event
     *
     * @return void
     */
    public function register(RelationshipBuildEvent $event)
    {
        $relationship = $event->getRelationship();

        if (!$relationship instanceof TargetableInterface) {
            return;
        }

        if (!$relationship->hasUrl()) {
            return;
        }

        $this->targets->attach(
            $relationship->getTarget(),
            $relationship->getUrl()
        );
    }

    /**
     * Publish all messages to crawl all new resources found
     *
     * @param PostResponseEvent $event
     *
     * @return void
     */
    public function publish(PostResponseEvent $event)
    {
        foreach ($this->targets as $resource) {
            $this->producer->publish(serialize([
                'url' => $this->targets[$resource],
                'uuid' => $resource->getUuid(),
                'host' => $event->getRequest()->getHost(),
            ]));
        }
    }
}
