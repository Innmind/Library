<?php

namespace APIBundle\EntityFactory;

use APIBundle\EntityFactoryInterface;
use APIBundle\Graph\Node\Html;
use Innmind\Rest\Server\HttpResourceInterface;

class HtmlFactory implements EntityFactoryInterface
{
    protected $resourceFactory;
    protected $imagesFactory;
    protected $linksFactory;
    protected $authorFactory;
    protected $citationsFactory;

    public function __construct(
        HttpResourceFactory $resourceFactory,
        ImagesFactory $imagesFactory,
        LinksFactory $linksFactory,
        AuthorFactory $authorFactory,
        CitationsFactory $citationsFactory
    ) {
        $this->resourceFactory = $resourceFactory;
        $this->imagesFactory = $imagesFactory;
        $this->linksFactory = $linksFactory;
        $this->authorFactory = $authorFactory;
        $this->citationsFactory = $citationsFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(HttpResourceInterface $resource)
    {
        $definition = $resource->getDefinition();

        if (!$definition->hasOption('class')) {
            return false;
        }

        return $definition->getOption('class') === Html::class;
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

        $this->resourceFactory->build($resource, $entity);

        $entity
            ->setContent($resource->get('content'))
            ->setDescription($resource->get('description'))
            ->setAnchors($resource->get('anchors'))
            ->setJournal(
                $resource->has('journal') ? $resource->get('journal') : false
            )
            ->setTitle($resource->get('title'));

        if ($resource->has('language')) {
            $entity->setLanguage($resource->get('language'));
        }

        if ($resource->has('theme_color')) {
            $entity->setThemeColor($resource->get('theme_color'));
        }

        if ($resource->has('rss')) {
            $entity->setRss($resource->get('rss'));
        }

        if ($resource->has('android')) {
            $entity->setAndroid($resource->get('android'));
        }

        if ($resource->has('ios')) {
            $entity->setIos($resource->get('ios'));
        }

        if ($this->imagesFactory->supports($resource)) {
            $this->imagesFactory->build($resource, $entity);
        }

        if ($this->linksFactory->supports($resource)) {
            $this->linksFactory->build($resource, $entity);
        }

        if ($this->authorFactory->supports($resource)) {
            $this->authorFactory->build($resource, $entity);
        }

        if ($this->citationsFactory->supports($resource)) {
            $this->citationsFactory->build($resource, $entity);
        }
    }
}
