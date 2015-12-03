<?php

namespace APIBundle;

use Innmind\Rest\Server\HttpResourceInterface;

interface EntityFactoryInterface
{
    /**
     * Check if the factory can build an entity out of the resource
     *
     * @param HttpResourceInterface $resource
     *
     * @return bool
     */
    public function supports(HttpResourceInterface $resource);

    /**
     * Transpose the resource into the entity
     *
     * @param HttpResourceInterface $resource
     * @param object $entity
     *
     * @return EntityFactoryInterface self
     */
    public function build(HttpResourceInterface $resource, $entity);
}
