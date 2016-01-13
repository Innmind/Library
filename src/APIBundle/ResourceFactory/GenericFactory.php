<?php

namespace APIBundle\ResourceFactory;

use APIBundle\ResourceFactoryInterface;
use Innmind\Neo4j\ONM\EntityManagerInterface;
use Innmind\Rest\Server\Definition\ResourceDefinition;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class GenericFactory implements ResourceFactoryInterface
{
    private $em;
    private $accessor;

    public function __construct(
        EntityManagerInterface $em,
        PropertyAccessorInterface $accessor
    ) {
        $this->em = $em;
        $this->accessor = $accessor;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, ResourceDefinition $definition)
    {
        return $this->em->contains($data);
    }

    /**
     * {@inheritdoc}
     */
    public function build($data, ResourceDefinition $definition)
    {
        $object = new \stdClass;

        foreach ($definition->getProperties() as $prop) {
            try {
                $this->accessor->isReadable($data, (string) $prop);
            } catch (\Exception $e) {
                continue;
            }

            $object->{(string) $prop} = $this->accessor->getValue(
                $data,
                (string) $prop
            );
        }

        return $object;
    }
}
