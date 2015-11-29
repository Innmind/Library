<?php

namespace APIBundle\EntityFactory;

use APIBundle\EntityFactoryInterface;
use Innmind\Rest\Server\HttpResourceInterface;

class DelegationFactory implements EntityFactoryInterface
{
    protected $factories;

    public function __construct(array $factories)
    {
        $this->factories = $factories;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(HttpResourceInterface $resource)
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($resource)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function build(HttpResourceInterface $resource, $entity)
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($resource)) {
                $factory->build($resource, $entity);
                break;
            }
        }
    }
}
