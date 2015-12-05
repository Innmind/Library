<?php

namespace APIBundle\Graph\Relationship;

use APIBundle\Graph\Node\HttpResource;

/**
 * Interface to be used when the target of the relationship is a HttpResource
 * (or a child) and that this resource is identified by a url in the relationship
 */
interface TargetableInterface
{
    /**
     * Set the resource at the target of the relationship
     *
     * @param HttpResource $target
     *
     * @return TargetableInterface self
     */
    public function setTarget(HttpResource $target);

    /**
     * Return the target resource (the one indicated by the url)
     *
     * @return HttpResource
     */
    public function getTarget();

    /**
     * Return the url designating the target resource
     *
     * @return string
     */
    public function getUrl();
}
