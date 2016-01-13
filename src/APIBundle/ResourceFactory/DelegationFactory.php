<?php

namespace APIBundle\ResourceFactory;

use APIBundle\ResourceFactoryInterface;
use Innmind\Rest\Server\Definition\ResourceDefinition;

class DelegationFactory implements ResourceFactoryInterface
{
    private $factories;

    public function __construct(array $factories)
    {
        $this->factories = $factories;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, ResourceDefinition $definition)
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($data, $definition)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function build($data, ResourceDefinition $definition)
    {
        $object = new \stdClass;

        foreach ($this->factories as $factory) {
            if (!$factory->supports($data, $definition)) {
                continue;
            }

            $inner = $factory->build($data, $definition);

            foreach ($inner as $property => $value) {
                $object->$property = $value;
            }
        }

        return $object;
    }
}
