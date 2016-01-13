<?php

namespace APIBundle;

use Innmind\Rest\Server\Definition\ResourceDefinition;

interface ResourceFactoryInterface
{
    /**
     * Check if the factory can build an entity out of the resource
     *
     * @param mixed $data
     * @param ResourceDefinition $definition
     *
     * @return bool
     */
    public function supports($data, ResourceDefinition $definition);

    /**
     * Transpose the resource into the entity
     *
     * @param mixed $data
     * @param ResourceDefinition $definition
     *
     * @return stdClass
     */
    public function build($data, ResourceDefinition $definition);
}
