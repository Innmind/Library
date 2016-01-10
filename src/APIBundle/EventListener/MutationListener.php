<?php

namespace APIBundle\EventListener;

use Innmind\Rest\Server\Events;
use Innmind\Rest\Server\Event\Storage\PreUpdateEvent;
use Innmind\Rest\Server\Definition\ResourceDefinition;
use Innmind\Neo4j\ONM\EntityManagerInterface;
use Innmind\Neo4j\ONM\Exception\EntityNotFoundException;
use Innmind\Neo4j\ONM\Query;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MutationListener implements EventSubscriberInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::STORAGE_PRE_UPDATE => 'mutateNode',
        ];
    }

    /**
     * Specialize a node by adding labels to it
     *
     * @param PreUpdateEvent $event
     *
     * @return void
     */
    public function mutateNode(PreUpdateEvent $event)
    {
        $id = $event->getResourceId();
        $definition = $event->getResource()->getDefinition();

        if (!$this->isMutable($definition, $id)) {
            return;
        }

        $class = $this->findExistingNode($definition, $id);

        if ($class === null) {
            return;
        }

        $this->mutate($class, $id, $definition);
    }

    /**
     * Check if the resource is mutable
     *
     * @param ResourceDefinition $definition
     * @param mixed $id
     *
     * @return bool
     */
    private function isMutable(ResourceDefinition $definition, $id)
    {
        if (!$definition->hasOption('class')) {
            return false;
        }

        if (!$definition->hasOption('mutable_from')) {
            return false;
        }

        $mutables = $definition->getOption('mutable_from');

        if (!is_array($mutables) || empty($mutables)) {
            return false;
        }

        try {
            $this->em->find($definition->getOption('class'), $id);

            return false; //don't mutate if the target note already exist
        } catch (EntityNotFoundException $e) {
            //continue
        }

        return true;
    }

    /**
     * Find an existing node from the allowed mutables with the given id
     *
     * @param ResourceDefinition $definition
     * @param mixed $id
     *
     * @return string|null The class if found
     */
    private function findExistingNode(ResourceDefinition $definition, $id)
    {
        foreach ($definition->getOption('mutable_from') as $class) {
            try {
                $this->em->find($class, $id);

                return $class;
            } catch (EntityNotFoundException $e) {
                //continue as other classes may match
            }
        }
    }

    /**
     * Mutate the node by setting the labels from the resource on it
     *
     * @param string $class
     * @param mixed $id
     * @param ResourceDefinition $definition
     *
     * @return void
     */
    private function mutate($class, $id, ResourceDefinition $definition)
    {
        $uow = $this->em->getUnitOfWork();
        $metadata = $uow
            ->getMetadataRegistry()
            ->getMetadata($class);
        $idProperty = $metadata->getId()->getProperty();
        $match = $this
            ->em
            ->getRepository($class)
            ->getQueryBuilder()
            ->matchNode(
                'node',
                $class,
                [$idProperty => $id]
            )
            ->getQuery();
        $labels = $uow
            ->getMetadataRegistry()
            ->getMetadata($definition->getOption('class'))
            ->getLabels();

        $query = new Query(sprintf(
            '%s SET node :%s;',
            substr($match->getCypher(), 0, -1),
            implode(':', $labels)
        ));
        $query->addVariable('node', $class);

        foreach ($match->getParameters() as $key => $param) {
            $query->addParameters(
                $key,
                $param,
                $match->getReferences()[$key]
            );
        }

        $uow->execute($query);
    }
}
