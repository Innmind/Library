<?php

namespace APIBundle\ResourceFactory;

use APIBundle\ResourceFactoryInterface;
use APIBundle\Graph\Node\HttpResource;
use Innmind\Neo4j\ONM\EntityManagerInterface;
use Innmind\Neo4j\ONM\Query;
use Innmind\Rest\Server\Definition\ResourceDefinition;

class HttpResourceFactory implements ResourceFactoryInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, ResourceDefinition $definition)
    {
        return $data instanceof HttpResource;
    }

    /**
     * {@inheritdoc}
     */
    public function build($data, ResourceDefinition $definition)
    {
        $hostOfDomain = $this->getHostOfDomain($data);
        $domain = $hostOfDomain->getDomain();
        $object = new \stdClass;
        $object->domain = $domain->getDomain();
        $object->tld = $domain->getTld();
        $object->host = $hostOfDomain->getHost()->getHost();

        return $object;
    }

    /**
     * Return the host/domain relationship for the given resource
     *
     * @param HttpResource $resource
     *
     * @return HostOfDomain
     */
    private function getHostOfDomain(HttpResource $resource)
    {
        $query = new Query(<<<CYPHER
MATCH (r:Resource {uuid: {where}.uuid})--(h:Host)-[hod:HostOfDomain]-(d:Domain) RETURN hod;
CYPHER
        );
        $query
            ->addVariable('r', 'Resource')
            ->addVariable('h', 'Host')
            ->addVariable('d', 'Domain')
            ->addVariable('hod', 'HostOfDomain')
            ->addParameters(
                'where',
                ['uuid' => $resource->getUuid()],
                ['uuid' => 'r.uuid']
            );
        $results = $this
            ->em
            ->getUnitOfWork()
            ->execute($query);

        if ($results->count() !== 1) {
            throw new \Exception('Domain not found');
        }

        return $results->current();
    }
}
